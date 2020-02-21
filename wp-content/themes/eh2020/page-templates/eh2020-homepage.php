<?php

/**
 * Template Name: EH 2020 Homepage
 * Template Post Type: page
 */

?>

<?php get_template_part('/includes/header'); ?>

<div id="primary" class="bg-black entry">

    <!-- Hero section -->
    <div class="hero-section bg-black" style="padding-top: 0rem">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <img src="<?php echo get_stylesheet_directory_uri() ?>/imgs/WWF-EH-Banner1000.png" class="img-fluid">
                        </div>
                        <div class="col-lg-6 text-center hero-text-padding">
                            <h1>EARTH HOUR 2020 FESTIVAL FOR NATURE</h1>
                            <h2>At I LIGHT Singapore</h2>
                            <h4>27 — 29 MARCH &nbsp; | &nbsp; TILL 10PM DAILY</h4>
                            <h4>THE FLOAT AT MARINA BAY</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Countdown -->
    <section id="timer" class="bg-black">
        <div class="container">
            <div class="countdown-wrapper text-center">
                <div id="countdown">
                    <div class="countdown-box">
                        <span id="day" class="timer"></span><span class="caption">DAYS</span>
                    </div>
                    <div class="countdown-box">
                        <span id="hour" class="timer"></span><span class="caption">HOURS</span>
                    </div>
                    <div class="countdown-box">
                        <span id="min" class="timer"></span><span class="caption">MINUTES</span>
                    </div>
                    <div class="countdown-box">
                        <span id="sec" class="timer"></span><span class="caption">SECONDS</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About -->
    <div id="about">
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-6">
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/imgs/about-eh.jpeg" class="img-fluid mb-3">
                </div>
                <div class="col-lg-6 text-white">
                    <h2 style="color: #FFE783;" class="text-center mb-3">ABOUT EARTH HOUR</h2>
                    <p>Earth Hour brings together people globally to act for nature and climate. Every year, we will countdown together across 180 countries and territories around the world to do one iconic thing: switch off the lights.</p>

                    <p>But it’s not about doing it to “save electricity”.</p>

                    <p>It is a symbol of unity. A symbol of hope. A symbol of power in collective action.</p>
                    <p>

                    </p>
                    <p>2020 is an important year as major decisions will be taken to protect people and nature. Our future for decades to come will depend on the decisions made now.</p>

                    <div class="text-center">
                        <a class="btn btn-outline-dark btn-gradient py-3 px-4" target="_blank" href="https://www.facebook.com/events/604866186929008/">Find Out More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="entry-content">
        <div id="voice" class="alignfull">
            <?php include(__DIR__ . '/../partials/voice.php'); ?>
        </div>
    </div>

</div><!-- #primary -->

<?php get_template_part('/includes/footer'); ?>