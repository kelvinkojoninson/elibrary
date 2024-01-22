// Import the functions you need from the SDKs
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
const firebaseConfig = {
    apiKey: "AIzaSyBvScYnlTjo6qUIbz83jwvl_G6K3ElaeVI",
    authDomain: "shaxigh.firebaseapp.com",
    databaseURL: "https://shaxigh.firebaseio.com",
    projectId: "shaxigh",
    storageBucket: "shaxigh.appspot.com",
    messagingSenderId: "907567908080",
    appId: "1:907567908080:web:3fbaba9c4c13f8faf42e35"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/favicon.png",
    };
    return self.registration.showNotification(
        title,
        options,
    );
});
