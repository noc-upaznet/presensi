self.addEventListener('push', function (event) {
    const data = event.data.json();

    const title = data.title || 'Push Notification';
    const options = {
        body: data.body,
        icon: data.icon,
        data: data.data || {},
        actions: data.actions || []
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});