<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
            <a class="navbar-brand" href="/">SIATO</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home
                    <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="riwayat/">Login</a>
                </li>
                </ul>
            </div>
            </div>
        </nav>
        <div class="container-fluid text-center">    
            <div class="row content">
                <div class="col-sm-2 sidenav"></div>
                <div class="col-sm-8 text-center">
                    <img :src="$root.app.url + 'images/logo.png'" class="img-responsive" alt="logo"> 
                    <hr><hr>
                    <h1>Welcome to SIATO</h1>
                    <p>SIATO adalah bengkel yang di dirikan pada tahun 2019. Bengkel ini menyediakan beberapa layanan seperti jual spareparts dan jasa service. Kami mempunyai visi yaitu ingin menekankan segi kualitas dengan motto utama SIATO, yaitu cepat dan akurat.</p>
                    <hr>
                    <h3>Alamat</h3>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7906.155636271006!2d110.41110414368977!3d-7.781573923238638!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59f02d398d5f%3A0x2160d0e3acadd50!2sUniversitas+Atma+Jaya+Yogyakarta+-+Kampus+4+Gedung+Theresa!5e0!3m2!1sid!2sid!4v1538129842314" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
                <div class="col-sm-2 sidenav"></div>
            </div>
        </div>
    </div>
</template>
