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
            font-family: monospace;
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

            .image-container {}

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
    <img class="wwf-logo" src="./images/wwf-logo.png" />
    <div class="image-container">
        <?php
        $user = !empty($_GET['user_name']) ? substr(strtolower($_GET['user_name']), 0, 30) : 'A panda';
        $health = !empty($_GET['health']) ? $_GET['health'] : "";
        $economy = !empty($_GET['economy']) ? $_GET['economy'] : "";
        $standardOfLiving = !empty($_GET['standardOfLiving']) ? $_GET['standardOfLiving'] : "";
        $custom_text = !empty($_GET['custom_text']) ? $_GET['custom_text'] : "";
        ?>
        <p>Dear Singapore,</p>
        <p>How are you holding up? What a start to 2020! Fear and uncertainty have gripped the world with relentless forest fires, devastating health emergencies and more.</p>
        <p>I’m anxious. You probably are too. </p>
        <p>I’m not used to worrying so much in Singapore, where we enjoy a good standard of living and a sense of well-being. But lately I’ve started to wonder if we are taking it all for granted.</p>
        <p>The world in 2020 seems like a pretty frightening place. Our demands on the planet are coming back to us. In my lifetime, I’ve seen more natural disasters caused by climate change and an unprecedented loss of wildlife around the world. </p>
        <p>So what will 2030 look like? Will I still be able to:</p>
        <?php if ($health || $economy || $standardOfLiving || $custom_text) : ?>
            <ul>
                <?php
                echo $standardOfLiving ? '<li><strong>Enjoy the quality of life we are accustomed to here in Singapore? </strong><br>With amazing natural green spaces and readily available, affordable seafood</li>' : '';
                echo $health ? '<li><strong>Maintain the good health I’ve enjoyed?</strong><br>With breathable air and food free of microplastics</li>' : '';
                echo $economy ? '<li><strong>See a bright and prosperous future for Singapore?</strong><br>With a future-proofed energy strategy and readiness for the new reality that our climate changed world will bring</li>' : '';
                ?>
            </ul>

            <?php if ($custom_text) { ?>
                <ul>
                    <li><?= $custom_text ?></li>
                </ul>
            <?php } ?>

        <?php else : ?>
            <style>
                body {
                    font-size: 14px;
                }
            </style>
        <?php endif; ?>
        <p>These fears are not something that future generations will have to deal with. I feel it. Today, I am writing to ask you to help ensure the well-being of Singapore’s people, our families and the economy. 2020 is a year of important decisions that will set the path for the next decade.</p>
        <p>I’m doing everything I can. But I cannot face this alone. In writing this letter I invite Singapore’s decision makers - our politicians, our community leaders, our businesses, our lawmakers - to help me understand how we can reach a 2030 free from the uncertainty and anxiety that I feel for it right now. For me, for my family, for my future children and beyond.</p>
        <p style="margin-top: 10px">Sincerely,</p>
        <p class="signature"><?= $user ?></p>

    </div>
</body>

</html>