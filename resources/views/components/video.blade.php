<div class="video">
    <div class="mt-100 mx-auto mb-0"></div>
    <div class="mt-100 mx-auto">

        <div class="thumbnail-img" data-aos="fade-up">
            <img class="img" src="{{ asset('image/2.jpg') }}" alt="">
            <button type="button" class="play-btn video-btn" data-toggle="modal" data-target="#videoModal">
                <img src="{{ asset('image/play.svg') }}" alt="">
            </button>
        </div>


    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">


            <div class="modal-body">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <!-- 16:9 aspect ratio -->
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="video" width="1118" height="629" src="https://www.youtube.com/embed/pp4I18hYwhQ"
                            title="29 ноября 2021 г." frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>


            </div>

        </div>
    </div>
</div>




