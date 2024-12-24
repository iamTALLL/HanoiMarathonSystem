<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ha Noi Marathon</title>
        <link rel="stylesheet" href="./css/main.css">
        <script src="https://unpkg.com/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="./js/main.js"></script>
        <script src="./js/vendor/jquery.imagesloader-1.0.1.js"></script>
        <style>
            .course {
                text-align: center; /* Center the heading */
                margin-bottom: 30px;
            }
            .course h2 {
                font-size: 2rem;
                color: #0044cc; /* Blue color */
                text-shadow: 2px 2px 4px rgba(0, 68, 204, 0.6); /* Blue shadow */
                margin-bottom: 20px;
            }

            .gallery {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 30px; /* Increase the gap between images */
                margin-top: 20px;
                padding: 0 15px;
            }

            .gallery-item {
                position: relative;
                overflow: hidden;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
            }

            .gallery-item img {
                width: 100%;
                height: auto;
                display: block;
                border-radius: 8px;
                transition: transform 0.3s ease;
            }

            .gallery-item:hover {
                transform: scale(1.05);
            }

            .details {
                position: absolute;
                top: 10px;
                left: 10px;
                background-color: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 10px;
                font-size: 14px;
                border-radius: 5px;
                display: none;
            }

            .gallery-item:hover .details {
                display: block;
            }

            .description {
                margin-top: 10px;
                font-size: 14px;
                color: #555;
                line-height: 1.4;
                padding: 0 15px;
            }
        </style>
    </head>
    <body>
        <div class="navbar">
            <img src="./image/logo.png" alt="Logo"> 
            <div class="race-info">
                <span class="name">HANOI MARATHON SYSTEM</span>
            </div>
            <div class="nav-buttons">
                <a href="signup.html">Sign Up</a>
                <a href="signin.html">Sign In</a>
            </div>
        </div>
        <div class="img-dispay">
            <img src="./image/bia.jpg" alt="display">
            <div class="overlay-text">
                <h1>COMING <br> <span>SOON</span></h1>
                <p>THIS DECEMBER</p>
            </div>
        </div>
        <div class="aboutus">
            <h1>ABOUT US</h1>
                <h2>What is Lorem Ipsum?</h2>
                    <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </p>
                <h2>Where does it come from?</h2>
                    <p>
                        Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
                    </p>
        </div>

        <!-- Course Gallery Section -->
        <div class="course" role="course">
            <h2>Explore out competitions!</h2>
            <ul class="gallery" id="gallery">
                <!-- Image 1 -->
                <li class="gallery-item">
                    <a href="https://dungcutheduc.vn/chay-marathon/" onclick="showDetails('Location 1: Description of the course location 1');">
                        <img src="./image/course1.jpg" alt="Course Location 1">
                        <div class="details">Location 1</div>
                    </a>
                    <div class="description">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    </div>
                </li>

                <!-- Image 2 -->
                <li class="gallery-item">
                    <a href="https://vnexpress.net/mua-pr-tai-vnexpress-marathon-hai-phong-2024-4828300.html" onclick="showDetails('Location 2: Description of the course location 2');">
                        <img src="./image/course2.jpg" alt="Course Location 2">
                        <div class="details">Location 2</div>
                    </a>
                    <div class="description">
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit.
                    </div>
                </li>

                <!-- Image 3 -->
                <li class="gallery-item">
                    <a href="https://www.chicagomarathon.com/runners/runner-information/" onclick="showDetails('Location 3: Description of the course location 3');">
                        <img src="./image/course3.jpg" alt="Course Location 3">
                        <div class="details">Location 3</div>
                    </a>
                    <div class="description">
                        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis.
                    </div>
                </li>

                <!-- Image 4 -->
                <li class="gallery-item">
                    <a href="https://www.eugenemarathon.com/marathon" onclick="showDetails('Location 4: Description of the course location 4');">
                        <img src="./image/course4.jpg" alt="Course Location 4">
                        <div class="details">Location 4</div>
                    </a>
                    <div class="description">
                        Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem.
                    </div>
                </li>

                <!-- Image 5 -->
                <li class="gallery-item">
                    <a href="https://chay365.com/giai-marathon-di-vao-lich-su-voi-42-runner-dat-sub-210-o-nhat-ban/" onclick="showDetails('Location 5: Description of the course location 5');">
                        <img src="./image/course5.jpg" alt="Course Location 5">
                        <div class="details">Location 5</div>
                    </a>
                    <div class="description">
                        Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore.
                    </div>
                </li>

                <!-- Image 6 -->
                <li class="gallery-item">
                    <a href="https://www.hcmcmarathon.com/vi/gioi-thieu-hcmc-marathon/" onclick="showDetails('Location 6: Description of the course location 6');">
                        <img src="./image/course6.jpg" alt="Course Location 6">
                        <div class="details">Location 6</div>
                    </a>
                    <div class="description">
                        Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur.
                    </div>
                </li>
            </ul>
        </div>

        <!-- Modal for showing course location details -->
        <div id="modal" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <p id="courseDetails"></p>
            </div>
        </div>

        <script>
            // Function to display course details
            function showDetails(details) {
                document.getElementById('courseDetails').innerText = details;
                document.getElementById('modal').style.display = "block";
            }

            // Function to close the modal
            function closeModal() {
                document.getElementById('modal').style.display = "none";
            }

            // Click outside of modal to close
            window.onclick = function(event) {
                if (event.target == document.getElementById('modal')) {
                    closeModal();
                }
            }
        </script>

    </body>
</html>
