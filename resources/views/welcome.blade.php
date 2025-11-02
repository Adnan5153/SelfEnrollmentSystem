<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>University</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    {{-- @yield('styles') --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        .header {
            min-height: 100vh;
            width: 100%;
            background-image: linear-gradient(rgba(4, 9, 30, 0.7), rgba(4, 9, 30, 0.7)), url(images/banner.png);
            background-position: center;
            background-size: cover;
            position: relative;
        }

        nav {
            display: flex;
            padding: 2% 6%;
            justify-content: space-between;
            text-align: center;
        }

        nav img {
            width: 150px;
        }

        .nav-links {
            flex: 1;
            text-align: right;
        }

        .nav-links ul li {
            list-style: none;
            display: inline-block;
            padding: 8px 12px;
            position: relative;
        }

        .nav-links ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            text-transform: uppercase;
        }

        .nav-links ul li a::after {
            content: '';
            width: 0%;
            height: 2px;
            background: #f44336;
            display: block;
            margin: auto;
            transition: 0.5s;
        }

        .nav-links ul li a:hover::after {
            width: 100%;
        }

        .text-box {
            width: 90%;
            color: #ffff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .text-box h1 {
            font-size: 62px;
        }

        .text-box p {
            margin: 10px 0 40px;
            font-size: 14px;
            color: #fff;
        }

        .hero-btn {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            border: 1px solid #fff;
            padding: 12px 34px;
            font-size: 13px;
            background: transparent;
            position: relative;
            cursor: pointer;
        }

        .hero-btn:hover {
            border: 1px solid #f44336;
            background: #f44336;
            transition: 1s;
        }

        nav .fa-solid {
            display: none;
        }

        .course {
            width: 80%;
            margin: auto;
            text-align: center;
            padding-top: 100px;
        }

        h1 {
            font-size: 36px;
            font-weight: 600;
        }

        p {
            color: #777;
            font-size: 14px;
            font-weight: 300;
            line-height: 22px;
            padding: 10px;

        }

        .row {
            margin-top: 5%;
            display: flex;
            justify-content: space-between;
        }

        .course-col {
            flex-basis: 31%;
            background: #fff3f3;
            border-radius: 10px;
            margin-bottom: 5%;
            padding: 20px 12px;
            box-sizing: border-box;
            transition: 0.5s;
        }

        .course-col:hover {
            box-shadow: 0 0 20px 0px rgba(0, 0, 0, 0.2);
        }

        .campus {
            width: 80%;
            margin: auto;
            text-align: center;
            padding-top: 50px;
        }

        .campus-col {
            flex-basis: 32%;
            border-radius: 10px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .campus-col img {
            width: 100%;
            display: block;
        }

        .layer {
            background: transparent;
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            transition: 0.5s;
        }

        .layer:hover {
            background: rgba(226, 0, 0, 0.7);
        }

        .layer h3 {
            width: 100%;
            font-weight: 500;
            color: #fff;
            font-size: 26px;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            position: absolute;
            opacity: 0;
            transition: 0.5s;
        }

        .layer:hover h3 {
            bottom: 49%;
            opacity: 1;
        }

        .facilities {
            width: 80%;
            margin: auto;
            text-align: center;
            padding-top: 100px;
        }

        .facilities-col {
            flex-basis: 31%;
            border-radius: 10px;
            margin-bottom: 5%;
            text-align: left;
        }

        .facilities-col img {
            width: 100%;
            border-radius: 10px;
        }

        .facilities-col p {
            padding: 0;
        }

        .facilities-col h3 {
            margin-top: 16px;
            margin-bottom: 15px;
            text-align: left;
        }

        .testimonial {
            width: 80%;
            margin: auto;
            text-align: center;
            padding-top: 100px;

        }

        .testimonial-col {
            flex-basis: 44%;
            border-radius: 10px;
            margin-bottom: 5%;
            text-align: left;
            background: #fff3f3;
            padding: 25px;
            cursor: pointer;
            display: flex;
        }

        .testimonial-col img {
            height: 40px;
            margin-left: 5px;
            margin-right: 30px;
            border-radius: 50%;
        }

        .testimonial-col p {
            padding: 0;
        }

        .testimonial-col h3 {
            margin-top: 15px;
            text-align: left;
        }

        @media(max-width: 700px) {
            .testimonial-col img {
                margin-left: 0px;
                margin-right: 15px;
            }
        }

        .cta {
            margin: 100px auto;
            width: 80%;
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url(images/banner2.jpg);
            background-position: center;
            background-size: cover;
            border-radius: 10px;
            text-align: center;
            padding: 100px 0;
        }

        .cta h1 {
            color: #fff;
            margin-bottom: 40px;
            padding: 0;
        }


        @media(max-width:700px) {
            .cta h1 {
                font-size: 24px;
            }
        }



        @media(max-width:700px) {
            .row {
                flex-direction: column;
            }
        }





        @media(max-width:700px) {
            .text-box h1 {
                font-size: 20px;
            }

            .nav-links ul li {
                display: block;
            }

            .nav-links {
                position: absolute;
                background: #f44336;
                height: 100vh;
                width: 200px;
                top: 0;
                right: -200px;
                text-align: left;
                z-index: 2;
                transition: 1s;
            }

            nav .fa-solid {
                display: block;
                color: #fff;
                margin: 10px;
                font-size: 22px;
                cursor: pointer;
            }

            .nav-links ul {
                padding: 30px;
            }
        }

        .footer {
            width: 100%;
            text-align: center;
            padding: 30px 0;
        }

        .footer h4 {
            margin-bottom: 25px;
            margin-top: 20px;
            font-weight: 600;
        }

        .icons .fa-brands {
            color: #f44336;
            margin: 0 13px;
            cursor: pointer;
            padding: 18px 0;
        }

        .fa-regular {
            color: #f44336;
        }
    </style>

</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <section class="header">
        <nav>
            <a href=""><img src="images/logohome.png" alt="logo"></a>
            <div class="nav-links" id="navLinks">
                <i class="fa-solid fa-xmark" onclick="hideMenu()"></i>
                <ul>
                    <li><a href="">Home</a></li>
                    <li><a href="">About</a></li>
                    <li><a href="">Course</a></li>
                    <li><a href="">Blog</a></li>
                    <li><a href="">Contact</a></li>
                    <li><a href="{{ route('userlogin') }}">Login</a></li>
                </ul>
            </div>
            <i class="fa-solid fa-bars" onclick="showMenu()"></i>
        </nav>
        <div class="text-box">
            <h1>World's Biggest University</h1>
            <p>Making website is now one of the easiest thing in world. you just need to learb HTML, CSS <br>Javascript
                and you are good to go.
            </p>
            <a href="" class="hero-btn">visit us To know more</a>
        </div>
    </section>

    <section class="course">
        <h1>Courses we offer</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        <div class="row">
            <div class="course-col">
                <h3>Intermediate</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Omnis fugit, explicabo aliquam accusantium
                    necessitatibus, debitis quod aperiam hic vero dolore </p>
            </div>
            <div class="course-col">
                <h3>Degree</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Omnis fugit, explicabo aliquam accusantium
                    necessitatibus, debitis quod aperiam hic vero dolore </p>
            </div>
            <div class="course-col">
                <h3>Post Graduation</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Omnis fugit, explicabo aliquam accusantium
                    necessitatibus, debitis quod aperiam hic vero dolore </p>
            </div>
        </div>
    </section>

    <section class="campus">
        <h1>Our Global campus</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>

        <div class="row">
            <div class="campus-col">
                <img src="images/london.png" alt="">
                <div class="layer">
                    <h3>LONDON</h3>
                </div>
            </div>
            <div class="campus-col">
                <img src="images/newyork.png" alt="">
                <div class="layer">
                    <h3>NEW YORK</h3>
                </div>
            </div>
            <div class="campus-col">
                <img src="images/washington.png" alt="">
                <div class="layer">
                    <h3>WASHINGTON</h3>
                </div>
            </div>
        </div>
    </section>

    <section class="facilities">
        <h1>Our facilities</h1>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>

        <div class="row">
            <div class="facilities-col">
                <img src="images/library.png" alt="">
                <h3>World class library</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam temporibus voluptas quisquam!</p>
            </div>
            <div class="facilities-col">
                <img src="images/basketball.png" alt="">
                <h3>Largest play Ground</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam temporibus voluptas quisquam!</p>
            </div>
            <div class="facilities-col">
                <img src="images/cafeteria.png" alt="">
                <h3>Tasty and healthy Food</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam temporibus voluptas quisquam!</p>
            </div>
        </div>
    </section>

    <section class="testimonial">
        <h1>What our students says</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        <div class="row">
            <div class="testimonial-col">
                <img src="images/user1.jpg" alt="">
                <div>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Itaque facere sequi odio incidunt!
                        Neque veniam rem eum eius harum delectus officiis voluptatum. Similique!
                    </p>
                    <h3>Cristine Berkley</h3>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
            </div>
            <div class="testimonial-col">
                <img src="images/user2.jpg" alt="">
                <div>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Itaque facere sequi odio incidunt!
                        Neque veniam rem eum eius harum delectus officiis voluptatum. Similique!
                    </p>
                    <h3>David Bayer</h3>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <h1>Enroll for our various online courses <br> anywhere from The World</h1>
        <a href="" class="hero-btn">CONTACT US</a>
    </section>

    <section class="footer">
        <h4>About us</h4>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem natus reprehenderit sint corporis molestiae
            illum. <br> Tenetur impedit dolor ipsa repudiandae asperiores quam esse enim.</p>
        <div class="icons">
            <i class="fa-brands fa-facebook-f"></i>
            <i class="fa-brands fa-twitter"></i>
            <i class="fa-brands fa-instagram"></i>
            <i class="fa-brands fa-linkedin-in"></i>
        </div>
        <p>Made with <i class="fa-regular fa-heart"></i> Borhan uddin</p>
    </section>






    <main class="mt-6">

    </main>

    <footer class="py-16 text-center text-sm text-black dark:text-white/70">

    </footer>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-----javascript for toogle menu------>
    <script>
        var navLinks = document.getElementById("navLinks");

        function showMenu() {
            navLinks.style.right = "0";
        }

        function hideMenu() {
            navLinks.style.right = "-200px";
        }
    </script>
</body>

</html>
