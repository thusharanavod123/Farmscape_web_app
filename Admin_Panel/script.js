// Fetch Messages and Display
function fetchMessages() {
  fetch("fetch_messages.php")
    .then(response => response.json())  // Parse directly as JSON
    .then(data => {
      console.log("Fetched Data:", data); // Log the data

      if (data && data.success) {
        const messageList = document.getElementById("messageList");
        messageList.innerHTML = ""; // Clear the list

        data.messages.forEach(msg => {
          const li = document.createElement("li");

          // Create a delete button
          const deleteBtn = document.createElement("button");
          deleteBtn.textContent = "Delete";
          deleteBtn.classList.add("delete-btn");
          deleteBtn.addEventListener("click", () => deleteMessage(msg.id));

          // Add message text and delete button to the list item
          li.textContent = `${msg.message} (Sent on: ${msg.created_at}) `;
          li.appendChild(deleteBtn);

          messageList.appendChild(li);
        });
      } else {
        alert("Failed to load messages");
      }
    })
    .catch(error => {
      console.error("Error fetching messages:", error);
    });
}

// Save New Message
function saveMessage() {
  const message = document.getElementById("messageBox").value;

  if (message.trim() === "") {
    alert("Message cannot be empty");
    return;
  }

  fetch("save_message.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `message=${encodeURIComponent(message)}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Message sent successfully");
      document.getElementById("messageBox").value = ""; // Clear the input
      fetchMessages(); // Refresh the message list
    } else {
      alert("Error sending message: " + data.error);
    }
  })
  .catch(error => {
    console.error("Error sending message:", error);
  });
}

// Delete Message
function deleteMessage(messageId) {
  if (confirm("Are you sure you want to delete this message?")) {
    fetch("delete_message.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `id=${encodeURIComponent(messageId)}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert("Message deleted successfully");
        fetchMessages(); // Refresh the message list
      } else {
        alert("Error deleting message: " + data.error);
      }
    })
    .catch(error => {
      console.error("Error deleting message:", error);
    });
  }
}

// Load messages on page load
window.onload = function() {
  fetchMessages();
  
  // Attach event listener for sending a message
  document.getElementById("sendMessageBtn").addEventListener("click", saveMessage);
};
