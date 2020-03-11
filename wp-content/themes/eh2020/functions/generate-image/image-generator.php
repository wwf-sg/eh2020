<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=2, shrink-to-fit=yes">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Earth Hour 2020</title>
    <style>
        /* latin-ext */
        @font-face {
            font-family: 'Mrs Saint Delafield';
            font-style: normal;
            font-weight: 400;
            src: local('Mrs Saint Delafield'), local('MrsSaintDelafield-Regular'), url(https://fonts.gstatic.com/s/mrssaintdelafield/v7/v6-IGZDIOVXH9xtmTZfRagunqBw5WC62QKcnL-mYF221gA.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }

        /* latin */
        @font-face {
            font-family: 'Mrs Saint Delafield';
            font-style: normal;
            font-weight: 400;
            src: local('Mrs Saint Delafield'), local('MrsSaintDelafield-Regular'), url(https://fonts.gstatic.com/s/mrssaintdelafield/v7/v6-IGZDIOVXH9xtmTZfRagunqBw5WC62QKknL-mYF20.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-size: 12px;
            font-family: sans-serif;
            line-height: 1.5;
            padding: 100px 40px 20px;
            width: 1200px;
            height: 630px;
        }

        .image-container {
            display: flex;
            flex-flow: column wrap;
            width: 100%;
            max-width: 1200px;
            max-height: 500px;
        }

        .image-container>* {
            width: 520px;
            flex: 0 1;
            margin-top: 0;
            margin-bottom: 8px;
        }

        .image-container hr {
            border: 0;
            border-bottom: 3px solid #000;
        }

        .signature {
            font-family: 'Mrs Saint Delafield', sans-serif;
            color: #000;
            font-size: 3rem;
            text-transform: capitalize;
            margin: 0;
            line-height: 1;
        }

        .wwf-logo {
            width: 50px;
            background-color: #fff;
            padding: .5rem;
            margin-left: 10px;
            position: absolute;
            top: 10px;
            left: 20px;
        }
    </style>

    <?php if (isset($_GET['short'])) : ?>
        <style>
            body {
                font-size: 12px;
                padding: 40px 20px 10px;
                max-width: 600px;
                max-height: 315px;
            }


            .image-container>* {
                width: 270px;
            }

            .wwf-logo {
                width: 30px;
                top: 0;
                left: 0
            }
        </style>
    <?php endif; ?>
</head>

<body>
    <!-- <img class="wwf-logo" src="./images/wwf-logo.png" /> -->
    <div class="image-container">
        <?php
        $user = !empty($_GET['user_name']) ? substr(strtolower($_GET['user_name']), 0, 30) : 'A panda';
        $feelings = "Hopeful"; // !empty($_GET['feelings']) ? $_GET['feelings'] : "";
        $health_1 = !empty($_GET['health_1']) ? $_GET['health_1'] : "";
        $health_2 = !empty($_GET['health_2']) ? $_GET['health_2'] : "";
        $future_1 = !empty($_GET['future_1']) ? $_GET['future_1'] : "";
        $future_2 = !empty($_GET['future_2']) ? $_GET['future_2'] : "";
        $qualityOfLiving_1 = !empty($_GET['qualityOfLiving_1']) ? $_GET['qualityOfLiving_1'] : "";
        $qualityOfLiving_2 = !empty($_GET['qualityOfLiving_2']) ? $_GET['qualityOfLiving_2'] : "";
        $custom_text = !empty($_GET['custom_text']) ? $_GET['custom_text'] : "";
        ?>
        <p>Dear Singapore,</p>
        <p>It’s been a rough start to 2020. Forest fires, health emergencies and more.</p>


        <p>I’m feeling <span style="text-transform: lowercase;"><?= $feelings ?></span>.</p>


        <p>I’m not used to worrying so much, and lately I’ve started to wonder if we are taking everything we have here in Singapore for granted.</p>
        <p>Nature is changing. We have lost much of the world’s biodiversity in the past 40 years. Climate change has become a matter of survival. Our demands on the planet are now coming back to us, and this is shaping how I live.</p>
        <p>So what will the future look like for me? Will I still be able to:</p>
        <ul>
            <?php if ($health_1 || $health_2) : ?>
                <li>
                    <strong>Enjoy good health</strong>
                    <ul>
                        <?php if ($health_1) { ?>
                            <li>With the air I breathe being free from haze?</li>
                        <?php } ?>
                        <?php if ($health_2) { ?>
                            <li>Will the food I eat be free of microplastics?</li>
                        <?php } ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($qualityOfLiving_1 || $qualityOfLiving_2) : ?>
                <li>
                    <strong>Maintain my quality of life </strong>
                    <ul>
                        <?php if ($qualityOfLiving_1) { ?>
                            <li>Will we still have natural green spaces for everyone across Singapore to enjoy?</li>
                        <?php } ?>
                        <?php if ($qualityOfLiving_2) { ?>
                            <li>Will all of the food I love be readily available and affordable?</li>
                        <?php } ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($future_1 || $future_2) : ?>
                <li>
                    <strong>Prosper</strong>
                    <ul>
                        <?php if ($future_1) { ?>
                            <li>Will I feel confident about my family’s future?</li>
                        <?php } ?>
                        <?php if ($future_2) { ?>
                            <li>Will I know that my home is safe from sea level rise and climate change?</li>
                        <?php } ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($custom_text) { ?>
                <li><?= $custom_text ?></li>
            <?php } ?>
        </ul>
        <p>This is the year for action. Let’s bring nature back.</p>
        <p>With this letter, I am asking our decision makers - Singapore’s political leaders, our businesses, our schools and institutions - to fight for a better future. In our policies, our workplaces and our homes, we want systemic change that restores nature and stops its destruction. When we do so, we protect everything good that comes along with it: clean air, food, water and a future for everyone. </p>
        <p style="margin-top: 10px">Sincerely,</p>
        <p class="signature"><?= $user ?></p>

    </div>
</body>

</html>