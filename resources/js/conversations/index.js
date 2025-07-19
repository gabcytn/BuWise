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

        this.selectedChat = document.querySelector(
            "li.conversation-item:first-child",
        ).dataset.chatId;

        this.messages = [];
        this.nextPageUrl = null;

        this.init();
    }

    init() {
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
                this.selectedChat = item.dataset.chatId;
                this.messagesList.innerHTML = "";
                this.loadInitialMessages();
            });
        });
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
        this.messages.forEach((msg) => {
            this.addMessage(msg.text, msg.sent, msg.time);
        });

        this.scrollToBottom();
    }

    async loadOlderMessages() {
        if (this.isLoading || !this.canLoadMore) return;

        this.isLoading = true;
        this.loadingIndicator.classList.add("show");

        // Store current scroll position
        const scrollHeight = this.messagesContainer.scrollHeight;

        console.log(this.nextPageUrl);
        const res = await fetch(this.nextPageUrl);
        const data = await res.json();

        const payload = data.messages;

        const nextPageUrl = payload.next_page_url;
        this.canLoadMore = nextPageUrl !== null;
        this.nextPageUrl = nextPageUrl;

        console.log(payload);

        const payloadData = payload.data;

        const olderMessages = payloadData.map((item) => {
            return {
                text: item.message,
                sent: item.sent,
                time: dayjs().to(dayjs(item.created_at)),
            };
        });

        const savedData = JSON.parse(
            sessionStorage.getItem(`chat-${this.selectedChat}`),
        );
        console.log(savedData);
        savedData.messages.unshift(...olderMessages.reverse());
        savedData.nextPageUrl = nextPageUrl;
        sessionStorage.setItem(
            `chat-${this.selectedChat}`,
            JSON.stringify(savedData),
        );

        // Add older messages to the top
        olderMessages.reverse().forEach((msg) => {
            this.prependMessage(msg.text, msg.sent, msg.time);
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

    updateChatListOrder() {
        const currentChat = document.querySelector(
            `li.conversation-item[data-chat-id='${this.selectedChat}']`,
        );

        const chatList = document.querySelector("ul.conversation-list");
        chatList.insertBefore(currentChat, chatList.firstChild);
    }

    updateChatItemLastMessage(message) {
        const currentChatItem = document.querySelector(
            `li.conversation-item[data-chat-id='${this.selectedChat}']`,
        );

        currentChatItem.querySelector(".message").textContent = message;
        currentChatItem.querySelector(".time").textContent = "Just now";
    }

    appendInSessionStorage(message) {
        const storedData = JSON.parse(
            sessionStorage.getItem(`chat-${this.selectedChat}`),
        );

        storedData.messages.push({
            text: message,
            sent: true,
            time: dayjs().to(dayjs(new Date())),
        });
        sessionStorage.setItem(
            `chat-${this.selectedChat}`,
            JSON.stringify(storedData),
        );
    }

    addMessage(text, sent, time) {
        const messageElement = this.createMessageElement(text, sent, time);
        this.messagesList.appendChild(messageElement);
        this.messageId++;
    }

    prependMessage(text, sent, time) {
        const messageElement = this.createMessageElement(text, sent, time);
        this.messagesList.insertBefore(
            messageElement,
            this.messagesList.firstChild,
        );
    }

    createMessageElement(text, sent, time) {
        const messageDiv = document.createElement("div");
        messageDiv.className = `message ${sent ? "sent" : "received"}`;

        messageDiv.innerHTML = `
                    <div class="message-bubble">
                        ${text}
                        <div class="message-time">${time}</div>
                    </div>
                `;

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
