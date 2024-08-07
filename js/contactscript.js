document.addEventListener("DOMContentLoaded", function() {
    const sendButton = document.getElementById("sendButton");
    sendButton.addEventListener("click", function(event) {
        event.preventDefault();

        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const reason = document.getElementById("reason").value;
        const message = document.getElementById("message").value;

        if (name && email && reason && message) {
            const webhookUrl = "https://discord.com/api/webhooks/1268568000584220834/V1k0ojk6S4PA-UfXxUvyTttvdI28f7Fk72dwNsAsAKDGte3cA-WfkOh6Aw3uIpQ7ayVM"; // Hier die URL deines Discord-Webhooks einfügen

            const payload = {
                content: "New contact form submission",
                embeds: [
                    {
                        title: "Contact Form Submission",
                        fields: [
                            {
                                name: "Name",
                                value: "**" + name + "**",
                                inline: true
                            },
                            {
                                name: "Email",
                                value: "**" + email + "**",
                                inline: true
                            },
                            {
                                name: "Reason for Contact",
                                value: "**" + reason + "**",
                                inline: true
                            },
                            {
                                name: "Message",
                                value: message,
                                inline: false
                            }
                        ],
                        timestamp: new Date().toISOString()
                    }
                ]
            };

            fetch(webhookUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            })
            .then(response => {
                if (response.ok) {
                    alert("Message sent successfully!");
                } else {
                    alert("Error sending message.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Error sending message.");
            });
        } else {
            alert("Please fill in all fields.");
        }
    });
});
