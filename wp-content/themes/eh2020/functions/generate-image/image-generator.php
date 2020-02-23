<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>WWF Plastic Alerts</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Mrs+Saint+Delafield&display=swap" rel="stylesheet">
    <style>
        /* @font-face {
            font-family: "Impact Label";
            src: url("../fonts/impactlabel.woff2") format("woff2"),
                url("../fonts/impactlabel.woff") format("woff");
            font-weight: normal;
            font-style: normal;
        } */

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
            font-family: 'Roboto', sans-serif;
            line-height: 1.5;
            padding: 60px 20px 0;
        }

        .image-container {
            display: flex;
            flex-flow: column wrap;
            width: 1000px;
            max-width: 1000px;
            height: 522px;
            max-height: 522px;
        }

        .image-container>* {
            width: 475px;
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
            font-size: 3rem;
            text-transform: lowercase;
            margin: 0;
            line-height: 1;
        }
    </style>
</head>

<body>
    <img src="./images/wwf-logo.png" style="width: 40px; background-color: #fff; padding: .5rem; margin-left: 10px; position: absolute; top: 0; left: 0;" />
    <div class="image-container">
        <?php
        $user = !empty($_GET['user_name']) ? substr(strtolower($_GET['user_name']), 0, 30) : 'A panda';
        $text = !empty($_GET['text']) ? $_GET['text'] : "";
        ?>
        <?php stripslashes($text); ?>
        <p>Dear Singapore</p>
        <p>How are you holding up?</p>
        <p>What a start to 2020! Fear and uncertainty have gripped the word with relentless news coverage on devastating fires, health emergencies and more.</p>
        <p>I’m anxious. You probably are too. </p>
        <p>We’re not used to having to worry quite so much. Here in Singapore we’ve enjoyed a great standard of living, good health and a reassuring sense of well-being. </p>
        <p>But as we’ve continued to depend on the country’s prosperity and growing affluence, we’ve also taken a lot of it for granted. And now the very things we’ve enjoyed so casually suddenly seem not so casual. </p>
        <p>Problems we’ve heard about always on the sidelines have escalated to levels we cannot ignore. </p>
        <ul>
            <li>The seafood we’ve always enjoyed is not so readily available, nor is it at the price we’ve come to expect.</li>
            <li>The pockets of remaining natural green spaces we’ve come to cherish are slowly giving way to concrete jungle.</li>
            <li>The food we eat is being contaminated by microplastics, so much so that we could be consuming five grams of it per week.</li>
            <li>Our health is threatened by zoonotic diseases linked to the illegal wildlife trade.</li>
            <li>Even the air we breathe is polluted with choking haze for months of the year.</li>
            <?php if ($text) : ?>
                <li><?= $text ?></li>
            <?php endif; ?>
        </ul>
        <p>The climate crisis is no longer something we expect future generations to have to deal with - it’s become a topic of every day conversation.</p>
        <p>Let’s face it - for too long we have taken too much from nature - and now we have to start paying the price. </p>
        <p>Today, I am writing to ask for your help in ensuring the future and well-being of Singapore’s people, families and economy. </p>
        <p>If we come together, as a people, united in our fight to move forward and progress but not at the expense of this one planet and the limited, precious resources it can provide, then we shall thrive. We can work together to overcome. After all, does it really matter who puts out the fire when your house is burning?</p>
        <p>The days are long but the years are short. If our kids will still be able to play outside, our family reunions still come with seafood, …. depends on what we do in the next ten years. The days are long but the years are short, the key decisions need to be taken this year.</p>
        <p>I AM DOING EVERYTHING I CAN but time is running out so I call upon / empower… </p>
        <p>As time is running out, in writing this letter I call upon the decision makers of Singapore - her politicians, her community leaders, her businessmen and women, her legislators and judges - to take the lead in protecting her future. </p>
        <p>I dream of a day where I can live in Singapore without uncertainty, without anxiety; a Singapore and a planet that I will be proud for my children to inherit. </p>
        <p style="margin: 0">Sincerely,</p>
        <p class="signature"><?= $user ?></p>

    </div>
</body>

</html>