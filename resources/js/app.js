console.log("✅ app.js berhasil dimuat");

// === FORCE DARK/LIGHT BASED ON localStorage ===
if (
    localStorage.getItem("color-theme") === "dark" ||
    (!("color-theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)
) {
    document.documentElement.classList.add("dark");
} else {
    document.documentElement.classList.remove("dark");
}

// === START BOOTSTRAP & ALPINE ===
import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// === DARK MODE TOGGLE BUTTON ===
var themeToggleDarkIcon = document.getElementById("theme-toggle-dark-icon");
var themeToggleLightIcon = document.getElementById("theme-toggle-light-icon");

if (
    localStorage.getItem("color-theme") === "dark" ||
    (!("color-theme" in localStorage) &&
        window.matchMedia("(prefers-color-scheme: dark)").matches)
) {
    themeToggleLightIcon?.classList.remove("hidden");
} else {
    themeToggleDarkIcon?.classList.remove("hidden");
}

var themeToggleBtn = document.getElementById("theme-toggle");

themeToggleBtn?.addEventListener("click", function () {
    themeToggleDarkIcon?.classList.toggle("hidden");
    themeToggleLightIcon?.classList.toggle("hidden");

    if (localStorage.getItem("color-theme")) {
        if (localStorage.getItem("color-theme") === "light") {
            document.documentElement.classList.add("dark");
            localStorage.setItem("color-theme", "dark");
        } else {
            document.documentElement.classList.remove("dark");
            localStorage.setItem("color-theme", "light");
        }
    } else {
        if (document.documentElement.classList.contains("dark")) {
            document.documentElement.classList.remove("dark");
            localStorage.setItem("color-theme", "light");
        } else {
            document.documentElement.classList.add("dark");
            localStorage.setItem("color-theme", "dark");
        }
    }
});

// === FLATPICKR SUPPORT ===
import flatpickr from "flatpickr";
window.flatpickr = flatpickr;

// === LUCIDE ICONS ===
import { createIcons, icons } from 'lucide';

document.addEventListener('DOMContentLoaded', function () {
    if (typeof createIcons === 'function') {
        createIcons({ icons });
        console.log('✅ Lucide icons loaded');
    } else {
        console.warn('⚠️ Lucide icons not loaded properly');
    }
});



// Ambil user ID dari meta tag
const userMeta = document.head.querySelector('meta[name="user-id"]');
const userId = userMeta ? userMeta.content : null;

if (userId) {
    console.log('📡 Subscribed to user:', userId);

window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        console.log('📥 Notifikasi diterima:', notification);
        showNotification(notification.message, notification.link, notification.time);
    });
}

// Fungsi tampilkan notifikasi baru ke UI
function showNotification(message, link, time = null) {
    const notifList = document.getElementById('notificationList');
    const notifBadge = document.getElementById('notificationBadge');

    const waktu = time ?? new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    if (notifList) {
        const li = document.createElement('li');
        li.className = 'p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer flex justify-between items-center';
        li.innerHTML = `
            <a href="${link}" class="block text-sm text-gray-800 dark:text-gray-100 w-11/12 truncate">${message}</a>
            <span class="text-xs text-gray-400">${waktu}</span>
        `;
        notifList.prepend(li);

        // Batasi hanya 3 notifikasi
        while (notifList.children.length > 3) {
            notifList.removeChild(notifList.lastChild);
        }
    }

    notifBadge?.classList.remove('hidden');
}




document.addEventListener('DOMContentLoaded', () => {
    const mobileSidebarButton = document.querySelector('[onclick*="mobile-sidebar"]');
    const notifDropdown = document.getElementById('notifDropdown');

    if (mobileSidebarButton && notifDropdown) {
        mobileSidebarButton.addEventListener('click', () => {
            if (!notifDropdown.classList.contains('hidden')) {
                notifDropdown.classList.add('hidden');
            }
        });
    }
});

import tinymce from 'tinymce/tinymce';
import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/code';
import 'tinymce/plugins/image';
import 'tinymce/plugins/table';

document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector('#content')) {
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: false,
            plugins: 'lists link image code table',
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code'
        });
    }
});


