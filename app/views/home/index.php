<?php
// app/views/home/index.php - Página principal con carrusel
?>
<div class="container-fluid p-0">
    <!-- CARRUSEL -->
    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="carousel-img-wrapper">
                    <img src="app/img/wc1.jpg" class="carousel-img" alt="Banner 1">
                    <div class="info-banner">
                        Información importante del banner 1
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-img-wrapper">
                    <img src="app/img/wc2.jpg" class="carousel-img" alt="Banner 2">
                    <div class="info-banner">
                        Información importante del banner 2
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-img-wrapper">
                    <img src="app/img/wc3.jpg" class="carousel-img" alt="Banner 3">
                    <div class="info-banner">
                        Información importante del banner 3
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-img-wrapper">
                    <img src="app/img/wc4.jpg" class="carousel-img" alt="Banner 4">
                    <div class="info-banner">
                        Información importante del banner 4
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTONES A LA DERECHA -->
        <div class="carousel-controls-vertical">
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">‹</button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">›</button>
        </div>
    </div>
</div>