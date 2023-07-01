<head>
<style>
    /* Quote Bar */
    .quote-bar {
        width: 80%;
        margin: 0 auto;
    }

    /* Banner Container */
    .banner-container {
        position: relative;
    }

    /* Banner */
    .banner {
        display: none;
    }

    /* Quote Box */
    .quote.box {
        position: relative;
        width: 90%;
        height: 120px;
        overflow: hidden;
        margin: 0 auto;
    }

    /* Quote Image */
    .quote.box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
        overflow: hidden;
    }

    /* Quote Area */
    .quote_area {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        background-color: rgba(0, 0, 0, 0.7);
        padding: 10px;
        text-align: center;
        color: #fff;
        border-radius: 10px;
    }

    /* Slideshow Dots */
    .dot {
        height: 10px;
        width: 10px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        margin: 0 2px;
        transition: background-color 0.6s ease;
    }

    .dot.active {
        background-color: #717171;
    }
</style>

</head>
<!--Quote Bar-->
<div class="quote-bar">
    <div class="banner-container fade">
        <div class="banner fade">
            <div class="quote box">
                <img src="../img/theskygrasstree.png" alt="">
                <div class="quote_area">
                    <h4>“The expert in anything was once a beginner.” —Helen Hayes.</h4>
                </div>
            </div>
        </div>
        <div class="banner fade">
            <div class="quote box">
                <img src="../img/floatingislandwaterfall.png" alt="">
                <div class="quote_area">
                    <h4>Take charge of your attitude. Don't let someone else choose it for you.</h4>
                </div>
            </div>
        </div>
        <div class="banner fade">
            <div class="quote box">
                <img src="../img/pinktreebeautifulgreenery.png" alt="">
                <div class="quote_area">
                    <h4>Show respect for everyone who works for a living, regardless of how trivial their job.</h4>
                </div>
            </div>
        </div>
        <div class="banner fade">
            <div class="quote box">
                <img src="../img/rangeofmountainswaterfall.png" alt="">
                <div class="quote_area">
                    <h4>Keep it simple.</h4>
                </div>
            </div>
        </div>
        <div class="banner fade">
            <div class="quote box">
                <img src="../img/peakwithtreesandwaterfall.png" alt="">
                <div class="quote_area">
                    <h4>Be brave. Even if you're not, pretend to be. No one can tell the difference.</h4>
                </div>
            </div>
        </div>
        <div class="banner fade">
            <div class="quote box">
                <img src="../img/winterskytreescastle.png" alt="">
                <div class="quote_area">
                    <h4>Make a habit to do nice things for people who will never find out.</h4>
                </div>
            </div>
        </div>
        <div class="banner fade">
            <div class="quote box">
                <img src="../img/sunrisingbetweenmountainslandscape.png" alt="">
                <div class="quote_area">
                    <h4>Be modest. A lot was accomplished before you were born.</h4>
                </div>
            </div>
        </div>
        <!-- Add more banner slides here -->
    </div>

    <div style="text-align:center">
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
        <!-- Add more dots for each slide -->
    </div>
</div>

<!-- Slideshow Script -->
<script>
    let slideIndex = 0;
    showSlides();

    function showSlides() {
        let i;
        let slides = document.getElementsByClassName("banner");
        let dots = document.getElementsByClassName("dot");
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1;
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " active";
        setTimeout(showSlides, 6000); // Change slide every 6 seconds
    }
</script>