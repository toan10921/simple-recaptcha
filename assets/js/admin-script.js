jQuery(document).ready(function ($) {
    // window on load
    $(window).on('load', function () {
        // settimout function
        setTimeout(function () {
            // call ajax
            $.ajax({
                // ajax url
                url: simple_recaptcha.ajax_url,
                // method
                method: 'POST',
                // data
                data: {
                    // action
                    action: simple_recaptcha.action,
                },
                // success function
                success: function (response) {
                    if (response.success) {
                        console.log(response.data);
                        let lstBanners = [];
                        let data = response.data;
                        // check if data is array
                        if (Array.isArray(data)) {
                            // loop through the data
                            data.forEach(element => {
                                // check if element is object
                                if (typeof element === 'object') {
                                    if (element.url) {
                                        lstBanners.push(
                                            {
                                                'image': simple_recaptcha.banner_folder_url + element.image,
                                                'url': element.url
                                            }
                                        );
                                    }
                                }
                            });
                        }

                        // check if lstBanners is not empty
                        if (lstBanners.length > 0) {
                            // loop through the lstBanners
                            lstBanners.forEach(banner => {
                                // create a new image element
                                let img = new Image();
                                // set the source of the image
                                img.src = banner.image;
                                // append the image to the  .plugin-advertising-banner
                                // document.querySelector('.plugin-advertising-banner').appendChild(img);
                                let link = document.createElement('a');
                                link.href = banner.url;
                                link.target = '_blank';
                                // nofollow
                                link.rel = 'nofollow';
                                link.style.display = 'none';
                                link.appendChild(img);
                                document.querySelector('.plugin-advertising-banner').appendChild(link);
                            });
                        }

                        // active first banner

                        // get all banner

                        let banners = document.querySelectorAll('.plugin-advertising-banner a');

                        // check if images is not empty

                        if (banners.length > 0) {
                            // loop through the images
                            banners.forEach((banner, index) => {
                                // check if index is 0
                                if (index === 0) {
                                    // set the display of the image to block
                                    banner.style.display = 'block';
                                }
                            });
                        }

                        // auto change banner

                        // set the index to 0

                        let index = 0;

                        // set the interval

                        setInterval(() => {
                            // check if banners is not empty
                            if (banners.length > 0) {
                                // loop through the banners
                                banners.forEach((banner, i) => {
                                    // check if index is equal to i
                                    if (index === i) {
                                        // set the display of the banner to block
                                        banner.style.display = 'block';
                                    } else {
                                        // set the display of the banner to none
                                        banner.style.display = 'none';
                                    }
                                });

                                // check if index is less than banners length
                                if (index < banners.length - 1) {
                                    // increment the index
                                    index++;
                                } else {
                                    // set the index to 0
                                    index = 0;
                                }
                            }
                        }, 5000);
                    }
                }
            });
        }, 1000);
    });
});