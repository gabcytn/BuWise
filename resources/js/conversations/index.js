import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);

class ChatApp {
    constructor() {
        this.messagesContainer = document.getElementById("messagesContainer");
        this.messagesList = document.getElementById("messagesList");
        this.messageInput = document.getElementById("messageInput");
        this.sendButton = document.getElementById("sendButton");
        this.loadingIndicator = document.getElementById("loadingIndicator");
        this.conversationItems = document.querySelectorAll(
            "li.conversation-item",
        );

        this.messageId = 0;
        this.isLoading = false;
        this.canLoadMore = true;

        this.messages = [];
        this.nextPageUrl = null;

        this.init();
    }

    init() {
        const firstChatItem = document.querySelector(
            "li.conversation-item:first-child",
        );
        this.selectedChat = firstChatItem.dataset.chatId;
        firstChatItem.classList.add("active-chat");
        this.setupEventListeners();
        this.loadInitialMessages();
    }

    setupEventListeners() {
        // Send button click
        this.sendButton.addEventListener("click", () => this.sendMessage());

        // Enter key to send (without shift)
        this.messageInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });

        // Auto-resize textarea
        this.messageInput.addEventListener("input", () => {
            this.messageInput.style.height = "auto";
            this.messageInput.style.height =
                this.messageInput.scrollHeight + "px";
        });

        // Scroll event for loading older messages
        this.messagesContainer.addEventListener("scroll", () => {
            if (
                this.messagesContainer.scrollTop === 0 &&
                this.hasNextPage() &&
                !this.isLoading
            ) {
                this.loadOlderMessages();
            }
        });

        this.conversationItems.forEach((item) => {
            item.addEventListener("click", () => {
                this.conversationItems.forEach((item) => {
                    item.classList.remove("active-chat");
                });
                this.selectedChat = item.dataset.chatId;
                this.messagesList.innerHTML = "";
                item.classList.add("active-chat");
                this.loadInitialMessages();
            });
        });

        window.Echo.private(`user.${sessionStorage.getItem("userId")}`).listen(
            "NewChatMessage",
            (e) => {
                const msg = e.message.message;
                const chatId = e.message.conversation_id;
                if (chatId === parseInt(this.selectedChat))
                    this.addMessage(
                        msg,
                        false,
                        "Just now",
                        e.sender.profile_img,
                    );
                this.messageInput.value = "";
                this.messageInput.style.height = "auto";
                this.scrollToBottom();
                this.updateChatItemLastMessage(msg, chatId);
                this.appendInSessionStorage(
                    msg,
                    chatId,
                    false,
                    e.sender.profile_img,
                );
                this.updateChatListOrder(chatId);
            },
        );
    }

    hasNextPage() {
        return this.canLoadMore;
    }

    async loadInitialMessages() {
        const savedMessages = sessionStorage.getItem(
            `chat-${this.selectedChat}`,
        );
        if (savedMessages) {
            this.messages = JSON.parse(savedMessages).messages;
            this.nextPageUrl = JSON.parse(savedMessages).nextPageUrl;
            this.canLoadMore = this.nextPageUrl !== null;
            this.displayMessages();
            return;
        }
        const res = await fetch(`/conversations/${this.selectedChat}/messages`);
        const data = await res.json();
        const payload = data.messages;

        const nextPageUrl = payload.next_page_url;
        this.canLoadMore = nextPageUrl !== null;
        this.nextPageUrl = nextPageUrl;

        const payloadData = payload.data;
        const initialMessages = payloadData.reverse().map((item) => {
            return {
                text: item.message,
                sent: item.sent,
                time: dayjs().to(dayjs(item.created_at)),
                img: item.img,
            };
        });

        sessionStorage.setItem(
            `chat-${this.selectedChat}`,
            JSON.stringify({
                messages: initialMessages,
                nextPageUrl: nextPageUrl,
            }),
        );

        this.messages = initialMessages;
        this.displayMessages();
    }

    displayMessages() {
        console.log(this.messages);
        this.messages.forEach((msg) => {
            this.addMessage(msg.text, msg.sent, msg.time, msg.img);
        });

        this.scrollToBottom();
    }

    async loadOlderMessages() {
        if (this.isLoading || !this.canLoadMore) return;

        this.isLoading = true;
        this.loadingIndicator.classList.add("show");

        // Store current scroll position
        const scrollHeight = this.messagesContainer.scrollHeight;

        const res = await fetch(this.nextPageUrl);
        const data = await res.json();

        const payload = data.messages;

        const nextPageUrl = payload.next_page_url;
        this.canLoadMore = nextPageUrl !== null;
        this.nextPageUrl = nextPageUrl;

        const payloadData = payload.data;

        const olderMessages = payloadData.map((item) => {
            return {
                text: item.message,
                sent: item.sent,
                time: dayjs().to(dayjs(item.created_at)),
                img: item.img,
            };
        });

        const savedData = JSON.parse(
            sessionStorage.getItem(`chat-${this.selectedChat}`),
        );
        savedData.messages.unshift(...olderMessages.reverse());
        savedData.nextPageUrl = nextPageUrl;
        sessionStorage.setItem(
            `chat-${this.selectedChat}`,
            JSON.stringify(savedData),
        );

        // Add older messages to the top
        olderMessages.reverse().forEach((msg) => {
            this.prependMessage(msg.text, msg.sent, msg.time, msg.img);
        });

        // Restore scroll position
        this.messagesContainer.scrollTop =
            this.messagesContainer.scrollHeight - scrollHeight;

        this.loadingIndicator.classList.remove("show");
        this.isLoading = false;
    }

    async sendMessage() {
        const text = this.messageInput.value.trim();
        if (!text || text.length > 255) return;

        const res = await fetch(
            `/conversations/${this.selectedChat}/messages`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        "meta[name='csrf-token']",
                    ).content,
                },
                body: JSON.stringify({
                    message: text,
                }),
            },
        );

        if (!res.ok) throw new Error(`Failed to send message: ${res.status}`);

        this.addMessage(text, true, "Just now");
        this.messageInput.value = "";
        this.messageInput.style.height = "auto";
        this.scrollToBottom();
        this.updateChatItemLastMessage(text);
        this.appendInSessionStorage(text);
        this.updateChatListOrder();
    }

    updateChatListOrder(chatToRenderFirst = this.selectedChat) {
        const currentChat = document.querySelector(
            `li.conversation-item[data-chat-id='${chatToRenderFirst}']`,
        );

        const chatList = document.querySelector("ul.conversation-list");
        chatList.insertBefore(currentChat, chatList.firstChild);
    }

    updateChatItemLastMessage(message = "", chatToUpdate = this.selectedChat) {
        const currentChatItem = document.querySelector(
            `li.conversation-item[data-chat-id='${chatToUpdate}']`,
        );

        currentChatItem.querySelector(".message").textContent = message;
        currentChatItem.querySelector(".time").textContent = "Just now";
    }

    appendInSessionStorage(
        message = "",
        chatToInsertTo = this.selectedChat,
        sent = true,
        img = null,
    ) {
        const storedData = JSON.parse(
            sessionStorage.getItem(`chat-${chatToInsertTo}`),
        );

        if (!storedData) return;

        storedData.messages.push({
            text: message,
            sent: sent,
            time: dayjs().to(dayjs(new Date())),
            img: img,
        });
        sessionStorage.setItem(
            `chat-${chatToInsertTo}`,
            JSON.stringify(storedData),
        );
    }

    addMessage(text, sent, time, img = null) {
        const messageElement = this.createMessageElement(text, sent, time, img);
        this.messagesList.appendChild(messageElement);
        this.messageId++;
    }

    prependMessage(text, sent, time, image) {
        const messageElement = this.createMessageElement(
            text,
            sent,
            time,
            image,
        );
        this.messagesList.insertBefore(
            messageElement,
            this.messagesList.firstChild,
        );
    }

    createMessageElement(text, sent, time, img = null) {
        const messageDiv = document.createElement("div");
        messageDiv.className = `message ${sent ? "sent" : "received"}`;

        messageDiv.innerHTML = `
                    <div class="message-bubble">
                        ${text}
                        <div class="message-time">${time}</div>
                    </div>
                `;

        if (!sent && img) {
            const imgElement = document.createElement("img");
            imgElement.src = `${origin}/storage/profiles/${img}`;
            imgElement.classList.add("msg-img");
            messageDiv.prepend(imgElement);
        }

        return messageDiv;
    }

    scrollToBottom() {
        setTimeout(() => {
            this.messagesContainer.scrollTop =
                this.messagesContainer.scrollHeight;
        }, 100);
    }
}

// Initialize the chat app
document.addEventListener("DOMContentLoaded", () => {
    new ChatApp();
    document.querySelectorAll("p.time").forEach((item) => {
        const time = item.dataset.time;
        item.textContent = time ? dayjs().to(dayjs(time)) : "";
    });
});
