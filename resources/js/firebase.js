var firebaseConfig = {
    apiKey: "AIzaSyBvScYnlTjo6qUIbz83jwvl_G6K3ElaeVI",
    authDomain: "shaxigh.firebaseapp.com",
    databaseURL: "https://shaxigh.firebaseio.com",
    projectId: "shaxigh",
    storageBucket: "shaxigh.appspot.com",
    messagingSenderId: "907567908080",
    appId: "1:907567908080:web:3fbaba9c4c13f8faf42e35"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

const initServiceWorker = () => {
    if ("serviceWorker" in navigator) {
        navigator.serviceWorker
            .register("/firebase-messaging-sw.js")
            .then(function (registration) {
                console.log("Service Worker registered with scope:", registration.scope);
            })
            .catch(function (error) {
                console.error("Service Worker registration failed:", error);
            });
    }
}

const createToastMarkup = (uniqueId, title, body, url = '') => {
    return `
        <div id="${uniqueId}" class="toast mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="20000">
            <div class="toast-header">
                <img src="${APP_URL+'/favicon.png'}" height="30" class="rounded me-2" alt="">
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            ${url ? `<a href="${url}" class="text-gray-700" target="_blank"><div class="toast-body">${body}</div></a>` : `<div class="toast-body">${body}</div>`}
        </div>`;
}

const showToast = (uniqueId) => {
    const toastE = document.getElementById(uniqueId);
    if (toastE) {
        const toast = new bootstrap.Toast(toastE);
        toast.show();
    }
}

const storeFCM = async (response) => {
    const uniqueId = uuidv4();
    var toastMarkup = '';

    try {
        let formData = new FormData();
        formData.append("token", response);

        // Send a POST request to the create API endpoint
        const res = await fetch(APP_URL + '/api/users/fcm-token', {
            method: "POST",
            headers: {
                'Authorization': `Bearer ${apiToken}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        const {
            ok,
            msg
        } = await res.json();
        if (!ok) {
            // Display error message if request is not successful
            toastMarkup = createToastMarkup(uniqueId, 'Notifications', msg);
            return;
        }
        toastMarkup = createToastMarkup(uniqueId, 'Notifications', msg);
    } catch (error) {
        // Display error message on request failure
        toastMarkup = createToastMarkup(uniqueId, 'Notifications', error.message);
    }

    const toastElement = document.createElement('div');
    toastElement.innerHTML = toastMarkup;
    const container = document.getElementById('toast-container');
    if (container) {
        container.appendChild(toastElement);
    }

    showToast(uniqueId);
}

const initFirebaseMessagingRegistration = async () => {
    if (Notification.permission !== 'granted') {
        Notification.requestPermission()
            .then(function (permission) {
                if (permission === 'granted') {
                    return messaging.getToken();
                } else {
                    throw new Error('Notification permission denied');
                }
            })
            .then(function (token) {
                console.log('Token:', token);
                storeFCM(token);
            })
            .catch(function (err) {
                console.log('Token Error:', err);
            });
    }

    messaging.onMessage((payload) => {
        const title = payload.notification.title;
        const body = payload.notification.body;
        const url = payload.data.url;
        const uniqueId = uuidv4();
        const toastMarkup = createToastMarkup(uniqueId, title, body, url);
       
        const notification = new Notification(title, {
            body: body,
            icon: payload.notification.icon,
        });

        notification.addEventListener('click', () => {
            if (url) {
                window.open(url, '_blank');
            }
        });

        const toastElement = document.createElement('div');
        toastElement.innerHTML = toastMarkup;
        const container = document.getElementById('toast-container');
        if (container) {
            container.appendChild(toastElement);
        }
        showToast(uniqueId);
    });
}

initServiceWorker();
initFirebaseMessagingRegistration();
