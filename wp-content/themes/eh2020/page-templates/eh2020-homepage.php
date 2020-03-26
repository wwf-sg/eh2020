<?php

/**
 * Template Name: EH 2020 Homepage
 * Template Post Type: page
 */

?>

<?php get_template_part('/includes/header'); ?>

<div id="primary" class="entry">

    <!-- Hero section -->
    <!-- <div class="hero-section bg-black" style="padding-top: 0rem">
        <div class="container py-md-2">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    <img style="max-width: 800px;" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/EH-digital-banner.png" class="w-100 img-fluid">
                </div>
                <div class="col-12 text-center">
                    <div style="max-width: 500px" class="m-auto pt-5">
                        <h1>EARTH HOUR 2020</h1>
                        <h2><strong>LIVE</strong> ON YOUTUBE</h2>
                        <p class="text-white">Join #nofilter conversations with changemakers about the planetary emergency and live music by some of Singapore’s top artistes.</p>
                        <h3>28 MARCH 2020 <span class="d-none d-md-inline">&nbsp; | &nbsp;</span> <br class="d-md-none"> 5:30 PM to 8:30 PM</h3>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <section id="youtube" class="bg-black text-white text-center my-5">
        <div class="container">
            <div class="embed-responsive embed-responsive-16by9 mb-3">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/IwaIWvwRmBQ?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <h2>Stay tuned for our live event on <span style="color: red">YouTube</span></h2>
            <p class="text-center">
                <a target="_blank" href="https://www.youtube.com/channel/UCRvj6BrtcT-lMAoOS_aVz4w" class="btn btn-lg btn-red">SUBSCRIBE</a>
            </p>
        </div>
				<div id="timer"class="container pt-5">
            <div class="countdown-wrapper text-center">
                <div id="countdown" >
                    <div class="countdown-box">
                        <span id="day" class="timer">00</span><span class="caption">DAYS</span>
                    </div>
                    <div class="countdown-box">
                        <span id="hour" class="timer">00</span><span class="caption">HOURS</span>
                    </div>
                    <div class="countdown-box">
                        <span id="min" class="timer">00</span><span class="caption">MINUTES</span>
                    </div>
                    <div class="countdown-box">
                        <span id="sec" class="timer">00</span><span class="caption">SECONDS</span>
                    </div>
                </div>
            </div>
        </div>
				<div class="col-12 text-center">
                    <div style="max-width: 600px" class="m-auto pt-4">
                        <!-- <h1>EARTH HOUR 2020</h1>
                        <h2><strong>LIVE</strong> ON YOUTUBE</h2> -->
                        <p class="text-white">Join #nofilter conversations with changemakers about the planetary emergency and live music by some of Singapore’s top artistes.</p>
                        <h3>28 MARCH 2020 <span class="d-none d-md-inline">&nbsp; | &nbsp;</span> <br class="d-md-none"> 5:30 PM to 8:30 PM</h3>
                    </div>
                </div>
        <svg class="wave" viewBox="0 0 1024 307" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
                <filter x="-1.2%" y="-4.0%" width="102.3%" height="108.0%" filterUnits="objectBoundingBox" id="filter-1">
                    <feOffset dx="0" dy="2" in="SourceAlpha" result="shadowOffsetOuter1"></feOffset>
                    <feGaussianBlur stdDeviation="2" in="shadowOffsetOuter1" result="shadowBlurOuter1"></feGaussianBlur>
                    <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.5 0" type="matrix" in="shadowBlurOuter1" result="shadowMatrixOuter1"></feColorMatrix>
                    <feMerge>
                        <feMergeNode in="shadowMatrixOuter1"></feMergeNode>
                        <feMergeNode in="SourceGraphic"></feMergeNode>
                    </feMerge>
                </filter>
                <linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="linearGradient-red">
                    <stop stop-color="#D70A0A" offset="0%"></stop>
                    <stop stop-color="#000000" offset="100%"></stop>
                </linearGradient>
                <path d="M0,65.8418637 L42.6666667,78.8976438 C85.3333333,92.1986029 170.666667,117.942395 256,127.933438 C341.333333,137.556712 426.666667,131.427238 512,105.070499 C597.333333,78.7137596 682.666667,33.3556502 768,13.5574484 C853.333333,-5.8729849 938.666667,0.256489342 981.333333,3.75028966 L1024,6.998911 L1024,299 L0,299 L0,65.8418637 Z" id="path-3"></path>
            </defs>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="EH-Website" transform="translate(0.000000, -1418.000000)">
                    <g id="wave-(1)" filter="url(#filter-1)" transform="translate(0.000000, 1420.000000)">
                        <mask id="mask-4" fill="white">
                            <use xlink:href="#path-3"></use>
                        </mask>
                        <use id="Mask-Copy" fill="url(#linearGradient-red)" fill-rule="nonzero" transform="translate(512.000000, 149.500000) scale(1, -1) translate(-512.000000, -149.500000) " xlink:href="#path-3"></use>
                    </g>
                </g>
            </g>
        </svg>
    </section>

    <!-- Countdown -->
    <!-- <section id="timer" class="bg-black mb-5"> -->
        <!-- <div class="container">
            <div class="countdown-wrapper text-center">
                <div id="countdown">
                    <div class="countdown-box">
                        <span id="day" class="timer">00</span><span class="caption">DAYS</span>
                    </div>
                    <div class="countdown-box">
                        <span id="hour" class="timer">00</span><span class="caption">HOURS</span>
                    </div>
                    <div class="countdown-box">
                        <span id="min" class="timer">00</span><span class="caption">MINUTES</span>
                    </div>
                    <div class="countdown-box">
                        <span id="sec" class="timer">00</span><span class="caption">SECONDS</span>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- <svg class="wave" viewBox="0 0 1024 307" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
                <filter x="-1.2%" y="-4.0%" width="102.3%" height="108.0%" filterUnits="objectBoundingBox" id="filter-1">
                    <feOffset dx="0" dy="2" in="SourceAlpha" result="shadowOffsetOuter1"></feOffset>
                    <feGaussianBlur stdDeviation="2" in="shadowOffsetOuter1" result="shadowBlurOuter1"></feGaussianBlur>
                    <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.5 0" type="matrix" in="shadowBlurOuter1" result="shadowMatrixOuter1"></feColorMatrix>
                    <feMerge>
                        <feMergeNode in="shadowMatrixOuter1"></feMergeNode>
                        <feMergeNode in="SourceGraphic"></feMergeNode>
                    </feMerge>
                </filter>
                <linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="linearGradient-2">
                    <stop stop-color="#23398B" offset="0%"></stop>
                    <stop stop-color="#000000" offset="100%"></stop>
                </linearGradient>
                <path d="M0,65.8418637 L42.6666667,78.8976438 C85.3333333,92.1986029 170.666667,117.942395 256,127.933438 C341.333333,137.556712 426.666667,131.427238 512,105.070499 C597.333333,78.7137596 682.666667,33.3556502 768,13.5574484 C853.333333,-5.8729849 938.666667,0.256489342 981.333333,3.75028966 L1024,6.998911 L1024,299 L0,299 L0,65.8418637 Z" id="path-3"></path>
            </defs>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="EH-Website" transform="translate(0.000000, -1418.000000)">
                    <g id="wave-(1)" filter="url(#filter-1)" transform="translate(0.000000, 1420.000000)">
                        <mask id="mask-4" fill="white">
                            <use xlink:href="#path-3"></use>
                        </mask>
                        <use id="Mask-Copy" fill="url(#linearGradient-2)" fill-rule="nonzero" transform="translate(512.000000, 149.500000) scale(1, -1) translate(-512.000000, -149.500000) " xlink:href="#path-3"></use>
                    </g>
                </g>
            </g>
        </svg>
    </section> -->
<!-- 
    <section id="youtube" class="bg-black text-white text-center my-5 pt-5">
        <div class="container pt-5">
            <div class="embed-responsive embed-responsive-16by9 mb-3">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/IwaIWvwRmBQ?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <h2>Stay tuned for our live event on <span style="color: red">YouTube</span></h2>
            <p class="text-center">
                <a target="_blank" href="https://www.youtube.com/channel/UCRvj6BrtcT-lMAoOS_aVz4w" class="btn btn-lg btn-red">SUBSCRIBE</a>
            </p>
        </div>
        <svg class="wave" viewBox="0 0 1024 307" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
                <filter x="-1.2%" y="-4.0%" width="102.3%" height="108.0%" filterUnits="objectBoundingBox" id="filter-1">
                    <feOffset dx="0" dy="2" in="SourceAlpha" result="shadowOffsetOuter1"></feOffset>
                    <feGaussianBlur stdDeviation="2" in="shadowOffsetOuter1" result="shadowBlurOuter1"></feGaussianBlur>
                    <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.5 0" type="matrix" in="shadowBlurOuter1" result="shadowMatrixOuter1"></feColorMatrix>
                    <feMerge>
                        <feMergeNode in="shadowMatrixOuter1"></feMergeNode>
                        <feMergeNode in="SourceGraphic"></feMergeNode>
                    </feMerge>
                </filter>
                <linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="linearGradient-red">
                    <stop stop-color="#D70A0A" offset="0%"></stop>
                    <stop stop-color="#000000" offset="100%"></stop>
                </linearGradient>
                <path d="M0,65.8418637 L42.6666667,78.8976438 C85.3333333,92.1986029 170.666667,117.942395 256,127.933438 C341.333333,137.556712 426.666667,131.427238 512,105.070499 C597.333333,78.7137596 682.666667,33.3556502 768,13.5574484 C853.333333,-5.8729849 938.666667,0.256489342 981.333333,3.75028966 L1024,6.998911 L1024,299 L0,299 L0,65.8418637 Z" id="path-3"></path>
            </defs>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="EH-Website" transform="translate(0.000000, -1418.000000)">
                    <g id="wave-(1)" filter="url(#filter-1)" transform="translate(0.000000, 1420.000000)">
                        <mask id="mask-4" fill="white">
                            <use xlink:href="#path-3"></use>
                        </mask>
                        <use id="Mask-Copy" fill="url(#linearGradient-red)" fill-rule="nonzero" transform="translate(512.000000, 149.500000) scale(1, -1) translate(-512.000000, -149.500000) " xlink:href="#path-3"></use>
                    </g>
                </g>
            </g>
        </svg>
    </section> -->

    <!-- About -->
    <div id="about" class="py-5 text-center bg-black">
        <div class="container pb-5">
            <div class="row">
                <div class="col col-lg-8 offset-lg-2 text-white">
                    <h2 style="color: #FFE783;" class="mb-3">About Earth Hour</h2>
                    <p>Earth Hour brings together people globally to act for nature and climate. Every year, we will countdown together across 180 countries and territories around the world to do one iconic thing: switch off the lights.</p>

                    <p>It’s not about doing it to “save electricity”. It is a symbol of unity. A symbol of hope. A symbol of power in collective action.</p>

                    <p>For the first time, Singapore’s Earth Hour event will take the form of a globally accessible digital live stream this year.</p>

                    <p>Taking place on 28th March from 5.30 – 8.30pm SGT, Earth Hour 2020 - Live & Unplugged is a three-hour digital event of live music by prominent artistes and unscripted conversations with leading environmental changemakers on the exigency of the current planetary emergency. The live event will culminate in a symbolic lights-out at 8.30pm local time.</p>
                    </p>

                    <div class="text-center">
                        <a class="btn text-white btn-gradient mt-3" target="_blank" href="http://bit.ly/EH2020FB">RSVP for latest updates</a>
                    </div>
                </div>
            </div>
        </div>
        <svg class="wave" viewBox="0 0 1024 307" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
                <filter x="-1.2%" y="-4.0%" width="102.3%" height="108.0%" filterUnits="objectBoundingBox" id="filter-1">
                    <feOffset dx="0" dy="2" in="SourceAlpha" result="shadowOffsetOuter1"></feOffset>
                    <feGaussianBlur stdDeviation="2" in="shadowOffsetOuter1" result="shadowBlurOuter1"></feGaussianBlur>
                    <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.5 0" type="matrix" in="shadowBlurOuter1" result="shadowMatrixOuter1"></feColorMatrix>
                    <feMerge>
                        <feMergeNode in="shadowMatrixOuter1"></feMergeNode>
                        <feMergeNode in="SourceGraphic"></feMergeNode>
                    </feMerge>
                </filter>
                <linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="linearGradient-purple">
                    <stop stop-color="#D756B5" offset="0%"></stop>
                    <stop stop-color="#000000" offset="100%"></stop>
                </linearGradient>
                <path d="M0,65.8418637 L42.6666667,78.8976438 C85.3333333,92.1986029 170.666667,117.942395 256,127.933438 C341.333333,137.556712 426.666667,131.427238 512,105.070499 C597.333333,78.7137596 682.666667,33.3556502 768,13.5574484 C853.333333,-5.8729849 938.666667,0.256489342 981.333333,3.75028966 L1024,6.998911 L1024,299 L0,299 L0,65.8418637 Z" id="path-3"></path>
            </defs>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="EH-Website" transform="translate(0.000000, -1418.000000)">
                    <g id="wave-(1)" filter="url(#filter-1)" transform="translate(0.000000, 1420.000000)">
                        <mask id="mask-4" fill="white">
                            <use xlink:href="#path-3"></use>
                        </mask>
                        <use id="Mask-Copy" fill="url(#linearGradient-purple)" fill-rule="nonzero" transform="translate(512.000000, 149.500000) scale(1, -1) translate(-512.000000, -149.500000) " xlink:href="#path-3"></use>
                    </g>
                </g>
            </g>
        </svg>
    </div>


    <!-- Form -->
    <div class="entry-content">
			<a id="open-letter"></a>
        <div id="voice" class="alignfull">
            <?php include(__DIR__ . '/../partials/voice.php'); ?>

            <svg class="wave" viewBox="0 0 1024 307" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <defs>
                    <filter x="-1.2%" y="-4.0%" width="102.3%" height="108.0%" filterUnits="objectBoundingBox" id="filter-1">
                        <feOffset dx="0" dy="2" in="SourceAlpha" result="shadowOffsetOuter1"></feOffset>
                        <feGaussianBlur stdDeviation="2" in="shadowOffsetOuter1" result="shadowBlurOuter1"></feGaussianBlur>
                        <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.5 0" type="matrix" in="shadowBlurOuter1" result="shadowMatrixOuter1"></feColorMatrix>
                        <feMerge>
                            <feMergeNode in="shadowMatrixOuter1"></feMergeNode>
                            <feMergeNode in="SourceGraphic"></feMergeNode>
                        </feMerge>
                    </filter>
                    <linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="linearGradient-2">
                        <stop stop-color="#D756B5" offset="0%"></stop>
                        <stop stop-color="#000000" offset="100%"></stop>
                    </linearGradient>
                    <path d="M0,65.8418637 L42.6666667,78.8976438 C85.3333333,92.1986029 170.666667,117.942395 256,127.933438 C341.333333,137.556712 426.666667,131.427238 512,105.070499 C597.333333,78.7137596 682.666667,33.3556502 768,13.5574484 C853.333333,-5.8729849 938.666667,0.256489342 981.333333,3.75028966 L1024,6.998911 L1024,299 L0,299 L0,65.8418637 Z" id="path-3"></path>
                </defs>
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="EH-Website" transform="translate(0.000000, -1418.000000)">
                        <g id="wave-(1)" filter="url(#filter-1)" transform="translate(0.000000, 1420.000000)">
                            <mask id="mask-4" fill="white">
                                <use xlink:href="#path-3"></use>
                            </mask>
                            <use id="Mask-Copy" fill="url(#linearGradient-2)" fill-rule="nonzero" transform="translate(512.000000, 149.500000) scale(1, -1) translate(-512.000000, -149.500000) " xlink:href="#path-3"></use>
                        </g>
                    </g>
                </g>
            </svg>
        </div>
    </div>

    <!-- Line-Up -->
    <section class="line-up my-5 py-5 text-white text-center bg-black">
        <div class="container">
            <h2 class="mb-5">Performances</h2>
            <div class="row">
                <!-- <div class="col-6 col-md-4">
                    <div>
                        <img class="mx-auto" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/Musicians-08.png" alt="" />
                        <h3>Angelique Teo</h3>
                        <p>Host</p>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div>
                        <img class="mx-auto" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/Musicians-09.png" alt="" />
                        <h3>Charmaine Yee</h3>
                        <p>Host</p>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div>
                        <img class="mx-auto" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/Musicians-10.png" alt="" />
                        <h3>Paul Foster</h3>
                        <p>Host</p>
                    </div>
                </div> -->
                <div class="col-6 col-md-4">
                    <div>
                        <img class="mx-auto" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/Musicians-02.png" alt="" />
                        <h3>Nathan Hartono</h3>
                        <p>5:30PM to 8:30PM</p>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div>
                        <img class="mx-auto" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/Musicians-01.png" alt="" />
                        <h3>RRILEY</h3>
                        <p>5:30PM to 8:30PM</p>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div>
                        <img class="mx-auto" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/Musicians-03.png" alt="" />
                        <h3>Benjamin Kheng</h3>
                        <p>5:30PM to 8:30PM</p>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div>
                        <img class="mx-auto" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/Musicians-04.png" alt="" />
                        <h3>Preetipls</h3>
                        <p>5:30PM to 8:30PM</p>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div>
                        <img class="mx-auto" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/Musicians-07.png" alt="" />
                        <h3>Subhas</h3>
                        <p>5:30PM to 8:30PM</p>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div>
                        <img class="mx-auto" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/Musicians-05.png" alt="" />
                        <h3>Sezairi</h3>
                        <p>5:30PM to 8:30PM</p>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div>
                        <img class="mx-auto" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/Musicians-06.png" alt="" />
                        <h3>Inch Chua</h3>
                        <p>5:30PM to 8:30PM</p>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div>
                        <img class="mx-auto" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/Musicians-11.png" alt="" />
                        <h3>Yung Raja &amp; Fariz Jabba</h3>
                        <p>5:30PM to 8:30PM</p>
                    </div>
                </div>
            </div>
        </div>
        <svg class="wave d-none" viewBox="0 0 1024 307" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
                <filter x="-1.2%" y="-4.0%" width="102.3%" height="108.0%" filterUnits="objectBoundingBox" id="filter-1">
                    <feOffset dx="0" dy="2" in="SourceAlpha" result="shadowOffsetOuter1"></feOffset>
                    <feGaussianBlur stdDeviation="2" in="shadowOffsetOuter1" result="shadowBlurOuter1"></feGaussianBlur>
                    <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.5 0" type="matrix" in="shadowBlurOuter1" result="shadowMatrixOuter1"></feColorMatrix>
                    <feMerge>
                        <feMergeNode in="shadowMatrixOuter1"></feMergeNode>
                        <feMergeNode in="SourceGraphic"></feMergeNode>
                    </feMerge>
                </filter>
                <linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="linearGradient-">
                    <stop stop-color="#D756B5" offset="0%"></stop>
                    <stop stop-color="#000000" offset="100%"></stop>
                </linearGradient>
                <path d="M0,65.8418637 L42.6666667,78.8976438 C85.3333333,92.1986029 170.666667,117.942395 256,127.933438 C341.333333,137.556712 426.666667,131.427238 512,105.070499 C597.333333,78.7137596 682.666667,33.3556502 768,13.5574484 C853.333333,-5.8729849 938.666667,0.256489342 981.333333,3.75028966 L1024,6.998911 L1024,299 L0,299 L0,65.8418637 Z" id="path-3"></path>
            </defs>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="EH-Website" transform="translate(0.000000, -1418.000000)">
                    <g id="wave-(1)" filter="url(#filter-1)" transform="translate(0.000000, 1420.000000)">
                        <mask id="mask-4" fill="white">
                            <use xlink:href="#path-3"></use>
                        </mask>
                        <use id="Mask-Copy" fill="url(#linearGradient-)" fill-rule="nonzero" transform="translate(512.000000, 149.500000) scale(1, -1) translate(-512.000000, -149.500000) " xlink:href="#path-3"></use>
                    </g>
                </g>
            </g>
        </svg>
    </section>
</div><!-- #primary -->

<?php get_template_part('/includes/footer'); ?>