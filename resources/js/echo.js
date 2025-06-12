import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

window.Echo.channel("task.1").listen("NotificationReminders", (e) => {
    console.log(e.task);
});

startup();

async function fetchUserDetails() {
    const res = await fetch("/user", {
        headers: {
            Accept: "application/json",
        },
    });

    const data = await res.json();
    sessionStorage.setItem("id", data.id);
}

async function startup() {
    if (!sessionStorage.getItem("id")) await fetchUserDetails();
    const res = await fetch("/api/notifications");
    const data = await res.json();
    const notifs = data.notifications;
    if (notifs.length > 0) {
        notifs.forEach((notif) => {
            addItemInNotificationPanel(notif);
        });
    }
    window.Echo.private(
        `App.Models.User.${sessionStorage.getItem("id")}`,
    ).notification((notif) => {
        addItemInNotificationPanel(notif);
        document.querySelector("#notificationPanel").classList.remove("d-none");
    });
}

function addItemInNotificationPanel(notif) {
    const notifList = document.querySelector("#notifList");
    const parentContainer = document.createElement("div");
    parentContainer.className = "notification-item";
    parentContainer.dataset.id = notif.id;

    const icon = document.createElement("i");
    icon.className = "fa-solid fa-user-check notification-icon";

    const notifContent = document.createElement("div");
    notifContent.className = "notification-content";

    const notifTitle = document.createElement("div");
    notifTitle.className = "notification-title";
    notifTitle.textContent = notif.title;

    const notifTime = document.createElement("div");
    notifTime.className = "notification-time";
    notifTime.textContent = notif.created_at;

    notifContent.appendChild(notifTitle);
    notifContent.appendChild(notifTime);

    const span = document.createElement("span");
    span.className = "notification-close";
    span.textContent = "×";
    span.onclick = () => {
        notifList.removeChild(parentContainer);
        fetch(`/notifications/${notif.id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
        });
    };

    parentContainer.appendChild(icon);
    parentContainer.appendChild(notifContent);
    parentContainer.appendChild(span);

    notifList.appendChild(parentContainer);
}
