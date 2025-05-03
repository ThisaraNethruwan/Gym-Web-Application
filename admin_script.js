document.addEventListener("DOMContentLoaded", () => {
 

    users.forEach(user => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td><button class="delete-btn">Delete</button></td>`;
        userTable.appendChild(row);
    });

    // Add event listener for delete action
    userTable.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete-btn")) {
            const row = e.target.closest("tr");
            row.remove();
        }
    });

    // Handle adding new memberships
    document.querySelector("#add-membership-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const name = document.getElementById("membership-name").value;
        const price = document.getElementById("membership-price").value;
        if (name && !isNaN(price)) {
            const listItem = document.createElement("li");
            listItem.textContent = `${name} - $${price}`;
            document.getElementById("membership-list").appendChild(listItem);
            e.target.reset();
        } else {
            alert("Invalid membership details.");
        }
    });

    // Handle adding new activities
    document.querySelector("#add-activity-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const name = document.getElementById("activity-name").value;
        const time = document.getElementById("activity-time").value;
        if (name && time) {
            const listItem = document.createElement("li");
            listItem.textContent = `${name} - ${time}`;
            document.getElementById("activity-list").appendChild(listItem);
            e.target.reset();
        } else {
            alert("Invalid activity details.");
        }
    });

    document.addEventListener("DOMContentLoaded", () => {
        // Handle uploading images
        document.querySelector("#upload-image-form").addEventListener("submit", (e) => {
            e.preventDefault();
            
            const fileInput = document.getElementById("image-file");
            if (fileInput.files.length > 0) {
                const formData = new FormData();
                formData.append("image", fileInput.files[0]);
    
                fetch("upload_image.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.imagePath) {
                        // Display the uploaded image in the gallery preview
                        const img = document.createElement("img");
                        img.src = data.imagePath;
                        img.alt = "Uploaded image";
                        document.getElementById("gallery-preview").appendChild(img);
                    } else {
                        alert(data.error || "Failed to upload image.");
                    }
                })
                .catch(error => alert("Error uploading image."));
                
                fileInput.value = ""; // Clear the file input
            } else {
                alert("Please select an image.");
            }
        });
    });
    document.addEventListener("DOMContentLoaded", () => {
        const galleryPreview = document.getElementById("gallery-preview");
    
        // Event delegation to handle delete clicks on gallery images
        galleryPreview.addEventListener("click", (e) => {
            if (e.target.tagName === "IMG") {
                const image = e.target;
                const imagePath = image.src;
    
                fetch(`delete_image.php?image=${encodeURIComponent(imagePath)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            image.remove(); // Remove the image from the gallery
                        } else {
                            alert(data.error || "Failed to delete image.");
                        }
                    })
                    .catch(error => alert("Error deleting image."));
            }
        });
    });
    
    
});
