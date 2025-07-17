class ChatApp {
    constructor() {
        this.messagesContainer = document.getElementById("messagesContainer");
        this.messagesList = document.getElementById("messagesList");
        this.messageInput = document.getElementById("messageInput");
        this.sendButton = document.getElementById("sendButton");
        this.loadingIndicator = document.getElementById("loadingIndicator");

        this.messageId = 0;
        this.isLoading = false;
        this.canLoadMore = true;

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadInitialMessages();
        this.scrollToBottom();
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
                this.canLoadMore &&
                !this.isLoading
            ) {
                this.loadOlderMessages();
            }
        });
    }

    loadInitialMessages() {
        const initialMessages = [
            { text: "Hey! How are you doing?", sent: false, time: "10:30 AM" },
            {
                text: "I'm good, thanks! Just working on some projects",
                sent: true,
                time: "10:32 AM",
            },
            {
                text: "That sounds interesting! What kind of projects?",
                sent: false,
                time: "10:33 AM",
            },
            {
                text: "Building a mobile chat UI actually 😄",
                sent: true,
                time: "10:35 AM",
            },
            { text: "Cool! How's it going?", sent: false, time: "10:36 AM" },
            {
                text: "Pretty well! Just adding the scroll-to-load feature",
                sent: true,
                time: "10:38 AM",
            },
        ];

        initialMessages.forEach((msg) => {
            this.addMessage(msg.text, msg.sent, msg.time);
        });
    }

    async loadOlderMessages() {
        if (this.isLoading || !this.canLoadMore) return;

        this.isLoading = true;
        this.loadingIndicator.classList.add("show");

        // Store current scroll position
        const scrollHeight = this.messagesContainer.scrollHeight;

        // Simulate API call delay
        await new Promise((resolve) => setTimeout(resolve, 1000));

        // Generate older messages
        const olderMessages = [
            { text: "Good morning!", sent: false, time: "9:00 AM" },
            {
                text: "Morning! Ready for the day?",
                sent: true,
                time: "9:02 AM",
            },
            {
                text: "Absolutely! Got some coding to do",
                sent: false,
                time: "9:05 AM",
            },
            {
                text: "Same here! What are you working on?",
                sent: true,
                time: "9:07 AM",
            },
            { text: "A new chat application", sent: false, time: "9:10 AM" },
        ];

        // Add older messages to the top
        olderMessages.reverse().forEach((msg) => {
            this.prependMessage(msg.text, msg.sent, msg.time);
        });

        // Restore scroll position
        this.messagesContainer.scrollTop =
            this.messagesContainer.scrollHeight - scrollHeight;

        this.loadingIndicator.classList.remove("show");
        this.isLoading = false;

        // Simulate reaching the end of messages after a few loads
        this.messageId += olderMessages.length;
        if (this.messageId > 20) {
            this.canLoadMore = false;
        }
    }

    sendMessage() {
        const text = this.messageInput.value.trim();
        if (!text) return;

        this.addMessage(text, true, this.getCurrentTime());
        this.messageInput.value = "";
        this.messageInput.style.height = "auto";
        this.scrollToBottom();

        // Simulate response after a short delay
        setTimeout(
            () => {
                const responses = [
                    "That's interesting!",
                    "I see what you mean",
                    "Sounds good to me",
                    "Tell me more about that",
                    "That's a great idea!",
                    "I agree with you",
                    "How did that go?",
                    "That's awesome!",
                ];
                const randomResponse =
                    responses[Math.floor(Math.random() * responses.length)];
                this.addMessage(randomResponse, false, this.getCurrentTime());
                this.scrollToBottom();
            },
            1000 + Math.random() * 2000,
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
        console.log(this.messagesContainer.scrollTop);
        console.log(this.messagesContainer.scrollHeight);
        setTimeout(() => {
            this.messagesContainer.scrollTop =
                this.messagesContainer.scrollHeight;
        }, 100);
    }

    getCurrentTime() {
        return new Date().toLocaleTimeString("en-US", {
            hour: "numeric",
            minute: "2-digit",
            hour12: true,
        });
    }
}

// Initialize the chat app
document.addEventListener("DOMContentLoaded", () => {
    new ChatApp();
});
