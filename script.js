function navigateToProducts() {
    window.location.href = "productpage.html";
}


/*----------------------farmer page js -------------------------------------------*/
document.getElementById('cover-photo-input').addEventListener('change', function(event) {
    const coverPhoto = event.target.files[0];
    if (coverPhoto) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.cover-photo').style.backgroundImage = `url(${e.target.result})`;
        };
        reader.readAsDataURL(coverPhoto);
    }
});

document.getElementById('profile-pic-input').addEventListener('change', function(event) {
    const profilePic = event.target.files[0];
    if (profilePic) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-pic').src = e.target.result;
        };
        reader.readAsDataURL(profilePic);
    }
});

document.getElementById('upload-button').addEventListener('click', function() {
    const photoInput = document.getElementById('additional-photo-input');
    const noteInput = document.getElementById('photo-note');
    const photo = photoInput.files[0];
    const note = noteInput.value;

    if (photo) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const uploadSection = document.createElement('div');
            uploadSection.classList.add('upload-section');
            
            const img = document.createElement('img');
            img.src = e.target.result;

            const noteText = document.createElement('textarea');
            noteText.value = note;

            uploadSection.appendChild(img);
            uploadSection.appendChild(noteText);
            
            document.querySelector('.upload-photos-notes').appendChild(uploadSection);

            // Clear the inputs
            photoInput.value = '';
            noteInput.value = '';
        };
        reader.readAsDataURL(photo);
    }
});

/**----------------------------------DOCTORS PROFILE ------------------------------------------ */
document.getElementById('cover-photo-input').addEventListener('change', function(event) {
    const coverPhoto = event.target.files[0];
    if (coverPhoto) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.cover-photo').style.backgroundImage = `url(${e.target.result})`;
        };
        reader.readAsDataURL(coverPhoto);
    }
});

document.getElementById('profile-pic-input').addEventListener('change', function(event) {
    const profilePic = event.target.files[0];
    if (profilePic) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-pic').src = e.target.result;
        };
        reader.readAsDataURL(profilePic);
    }
});

document.getElementById('carousel-image-input').addEventListener('change', function(event) {
    const files = event.target.files;
    const carouselContainer = document.querySelector('.carousel-container');
    carouselContainer.innerHTML = ''; // Clear previous images

    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            carouselContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});

document.getElementById('add-review-button').addEventListener('click', function() {
    const reviewInput = document.getElementById('review-input');
    const reviewText = reviewInput.value;

    if (reviewText) {
        const review = document.createElement('div');
        review.classList.add('review');
        review.textContent = reviewText;

        document.querySelector('.reviews-container').appendChild(review);

        // Clear the input
        reviewInput.value = '';
    }
});


/**----------------------------find doctor page ----------------------------------- */

document.getElementById('find-location-button').addEventListener('click', function() {
    const locationInput = document.getElementById('location-input').value;
    if (locationInput) {
        findNearbyVeterinaryShops(locationInput);
    }
});

function findNearbyVeterinaryShops(location) {
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: location }, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            const map = new google.maps.Map(document.getElementById('map'), {
                center: results[0].geometry.location,
                zoom: 15
            });

            const service = new google.maps.places.PlacesService(map);
            service.nearbySearch({
                location: results[0].geometry.location,
                radius: 5000,
                type: ['veterinary_care']
            }, function(results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    results.forEach(function(place) {
                        new google.maps.Marker({
                            map: map,
                            position: place.geometry.location,
                            title: place.name
                        });
                    });
                }
            });
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

document.getElementById('find-doctor-button').addEventListener('click', function() {
    const doctorSearchInput = document.getElementById('doctor-search-input').value;
    if (doctorSearchInput) {
        findDoctorByName(doctorSearchInput);
    }
});

function findDoctorByName(name) {
    const doctors = [
        { name: 'Dr. John Doe', specialization: 'Veterinary Surgeon', contact: '123-456-7890' },
        { name: 'Dr. Jane Smith', specialization: 'Animal Nutritionist', contact: '123-456-7891' },
        // Add more doctors here
    ];

    const results = doctors.filter(doctor => doctor.name.toLowerCase().includes(name.toLowerCase()));
    const resultsContainer = document.getElementById('doctor-results');
    resultsContainer.innerHTML = '';

    if (results.length > 0) {
        results.forEach(doctor => {
            const doctorDiv = document.createElement('div');
            doctorDiv.classList.add('doctor');
            doctorDiv.innerHTML = `
                <h3>${doctor.name}</h3>
                <p>Specialization: ${doctor.specialization}</p>
                <p>Contact: ${doctor.contact}</p>
            `;
            resultsContainer.appendChild(doctorDiv);
        });
    } else {
        resultsContainer.innerHTML = '<p>No doctors found</p>';
    }
}

/*--------------------------------------CONTACT US PAGE---------------------------*/
document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;
    
    console.log('Email:', email);
    console.log('Message:', message);

    // Clear the form after submission
    this.reset();

    alert('Thank you for reaching out to us!');
});


/*------------------------------------------------------loginfor doctor-------------------------------------------*/

// script.js

// script.js

// script.js

document.getElementById('cover-photo-input').addEventListener('change', function(event) {
    const coverPhotoLabel = document.querySelector('.cover-photo label');
    coverPhotoLabel.textContent = event.target.files[0].name;
});

document.getElementById('profile-pic-input').addEventListener('change', function(event) {
    const profilePic = document.getElementById('profile-pic');
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        profilePic.src = e.target.result;
    };

    reader.readAsDataURL(file);
});

function previewImage(input, previewId) {
    const file = input.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        const preview = document.getElementById(previewId);
        preview.src = e.target.result;
    };

    reader.readAsDataURL(file);
}

document.getElementById('career-image1').addEventListener('change', function() {
    previewImage(this, 'preview1');
});

document.getElementById('career-image2').addEventListener('change', function() {
    previewImage(this, 'preview2');
});

document.getElementById('career-image3').addEventListener('change', function() {
    previewImage(this, 'preview3');
});





// Function to simulate image processing and show popup message
function processCowImage() {
    const cageNumber = document.getElementById("cow-cage-number").value;
    const imageUpload = document.getElementById("cow-image-upload").files[0];

    // Show popup if inputs are missing
    if (!cageNumber || !imageUpload) {
        showPopup("🚨 First, you need to add a photo and enter a cage number!");
        return;
    }

    // Simulate ML processing result (for demo purpose)
    const allAnimalsInCage = Math.random() > 0.5;
    const missingCows = Math.floor(Math.random() * 5) + 1; // Simulate a random number of missing cows

    // Show result popup message
    if (allAnimalsInCage) {
        showPopup(`🐄 All cows are in Cage ${cageNumber}.`);
    } else {
        showPopup(`🚨 Not all cows are here. ${missingCows} cow(s) are missing in Cage ${cageNumber}!`);
    }
}

// Function to display a popup with a specific message
function showPopup(message) {
    const popup = document.getElementById("cow-popup");
    const popupMessage = document.getElementById("cow-popup-message");
    
    popupMessage.textContent = message;
    popup.classList.remove("hidden");
    popup.style.display = "block";
}

// Function to close the popup
function closeCowPopup() {
    const popup = document.getElementById("cow-popup");
    popup.classList.add("hidden");
    popup.style.display = "none";
}

// Function to reset the form
function resetCowForm() {
    document.getElementById("cow-cage-number").value = "";
    document.getElementById("cow-image-upload").value = "";
    closeCowPopup();
}


