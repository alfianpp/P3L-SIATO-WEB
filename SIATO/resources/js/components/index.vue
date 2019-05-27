<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
            <a class="navbar-brand" href="#">SIATO</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home
                    <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="tentang/">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="riwayat/">Login</a>
                </li>
                </ul>
            </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <h1 class="my-4">SIATO</h1>
                    <div>
                        <img :src="$root.app.url + 'images/logo.png'" alt="logo" height="240px"> 
                    </div>
                </div>

                <div class="col-lg-9">
                    <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>

                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active">
                                <img class="d-block img-fluid" src="images/bengkel-motor-1.jpg" alt="First slide">
                            </div>

                            <div class="carousel-item">
                                <img class="d-block img-fluid" src="images/bengkel-motor-2.jpg" alt="Second slide">
                            </div>

                            <div class="carousel-item">
                                <img class="d-block img-fluid" src="images/bengkel-motor-3.jpg" alt="Third slide">
                            </div>
                        </div>

                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>

                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h3>Spareparts</h3>
                        </div>

                        <div class="col-md-6">
                            <form>
                                <div class="form-row">
                                    <div class="col">
                                        <input v-model="name" @change="getAllSpareparts" type="text" class="form-control" placeholder="Cari spareparts">
                                    </div>

                                    <div class="col">
                                        <select v-model="order_by" @change="getAllSpareparts" class="form-control">
                                            <option value="null" disabled>Urutkan berdasarkan</option>
                                            <option value="harga_jual|desc">Harga Tertinggi</option>
                                            <option value="harga_jual|asc">Harga Terendah</option>
                                            <option value="stok|desc">Stok Paling Banyak</option>
                                            <option value="stok|asc">Stok Paling Sedikit</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div v-for="(spareparts, index) in listSpareparts" v-bind:key="index" class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <a href="#">
                                    <img class="card-img-top" :src="$root.app.url + 'images/' + spareparts.url_gambar" alt="">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="#">{{ spareparts.nama }}</a>
                                    </h5>
                                    <p class="card-text">
                                        Merk: <b>{{ spareparts.merk }}</b> <br/>
                                        Sisa stok: <b>{{ spareparts.stok }}</b>
                                    </p>
                                    <h6>{{ spareparts.harga_jual | toCurrency }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="py-5 bg-dark">
            <div class="container">
                <p class="m-0 text-center text-white">Copyright &copy; 2019 SIATO.</p>
            </div>
        </footer>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            listSpareparts: null,
            name: null,
            order_by: null,
        }
    },
    methods: {
        getAllSpareparts() {
            axios.get(this.$root.app.url + 'api/data/spareparts/index/search', {
                params: {
                    name: this.name,
                    order_by: this.order_by,
                }
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listSpareparts = response.data.data
                }
            })
        },
    },
    created() {
        this.getAllSpareparts()
    },
}
</script>
