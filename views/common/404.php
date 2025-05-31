<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link rel="shortcut icon" href="<?= base_url($HN->setting("favicon")); ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url("public/404/404.css") ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"
        integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <div class="container">
        <h1 class="first-four">4</h1>
        <div class="cog-wheel1">
            <div class="cog1">
                <div class="top"></div>
                <div class="down"></div>
                <div class="left-top"></div>
                <div class="left-down"></div>
                <div class="right-top"></div>
                <div class="right-down"></div>
                <div class="left"></div>
                <div class="right"></div>
            </div>
        </div>
        <h1 class="second-four">4</h1>
        <p class="wrong-para">PAGE NOT FOUND !</p>
    </div>
    <script>
        let t1 = gsap.timeline();
        let t2 = gsap.timeline();
        t1.to(".cog1", {
            transformOrigin: "50% 50%",
            rotation: "+=360",
            repeat: -1,
            ease: Linear.easeNone,
            duration: 8
        });
        t2.fromTo(".wrong-para", {
            opacity: 0
        }, {
            opacity: 1,
            duration: 1,
            stagger: {
                repeat: -1,
                yoyo: true
            }
        });
    </script>
</body>

</html>