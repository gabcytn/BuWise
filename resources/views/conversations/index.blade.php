<x-app-layout title="Messages">
    @vite(['resources/css/conversations/index.css', 'resources/js/conversations/index.js'])
    <div class="container">
        <div class="chats">
            <ul class="conversation-list">
                @foreach ($conversations as $item)
                    <li class="conversation-item" data-chat-id="{{ $item->id }}">
                        <img class="conversation-profile" src="{{ $item->image }}" alt="Profile Picture"</img>
                        <div class="conversation-details">
                            <h3 title="{{ $item->name }}">{{ truncate($item->name, 20) }}</h3>
                            <div class="conversation-message">
                                <p class="message" title="{{ $item->latest_message }}">
                                    {{ truncate($item->latest_message) }}</p>
                                <p class="time" data-time="{{ $item->latest_message_time }}">
                                </p>
                            </div>
                        </div>
                    </li>
                @endforeach
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
