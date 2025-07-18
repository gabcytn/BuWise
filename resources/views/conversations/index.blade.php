<x-app-layout>
    @vite(['resources/css/conversations/index.css', 'resources/js/conversations/index.js'])
    <div class="container">
        <div class="chats">
            <ul class="conversation-list">
                <li class="conversation-item">
                    <img class="conversation-profile" src="{{ asset('storage/profiles/default.png') }}"
                        alt="Profile Picture"</img>
                    <div class="conversation-details">
                        <h3>Name</h3>
                        <div class="conversation-message">
                            <p class="message">My first message</p>
                            <p class="time">3h ago</p>
                        </div>
                    </div>
                </li>
                <li class="conversation-item">
                    <img class="conversation-profile" src="{{ asset('storage/profiles/default.png') }}"
                        alt="Profile Picture"</img>
                    <div class="conversation-details">
                        <h3>Name</h3>
                        <div class="conversation-message">
                            <p class="message">My first message</p>
                            <p class="time">3h ago</p>
                        </div>
                    </div>
                </li>
                <li class="conversation-item">
                    <img class="conversation-profile" src="{{ asset('storage/profiles/default.png') }}"
                        alt="Profile Picture"</img>
                    <div class="conversation-details">
                        <h3>Name</h3>
                        <div class="conversation-message">
                            <p class="message">My first message</p>
                            <p class="time">3h ago</p>
                        </div>
                    </div>
                </li>
                <li class="conversation-item">
                    <img class="conversation-profile" src="{{ asset('storage/profiles/default.png') }}"
                        alt="Profile Picture"</img>
                    <div class="conversation-details">
                        <h3>Name</h3>
                        <div class="conversation-message">
                            <p class="message">My first message</p>
                            <p class="time">3h ago</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="chat-container">
            <div class="messages-container" id="messagesContainer">
                <div class="loading-indicator" id="loadingIndicator">Loading older messages...</div>
                <div id="messagesList">
                </div>
            </div>

            <div class="input-container">
                <div class="input-wrapper">
                    <textarea class="message-input" id="messageInput" placeholder="Type a message..." rows="1"></textarea>
                </div>
                <div class="button-wrapper">
                    <button class="send-button" id="sendButton">➤</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
