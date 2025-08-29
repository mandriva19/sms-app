jQuery(function($) {
    let lastId = null;

    const animations = [
        "animate__shakeX", "animate__shakeY", "animate__flash",
        "animate__bounce", "animate__headshake", "animate__heartbeat",
        "animate__backInRight", "animate__bounceIn", "animate__bounceInLeft",
        "animate__bounceInRight", "animate__fadeInDown", "animate__fadeInLeft",
        "animate__flipInX", "animate__lightSpeedInLeft", "animate__zoomInLeft",
        "animate__slideInDown", "animate__slideInLeft", "animate__slideInRight"
    ];

    function getRandomAnimation() {
        return animations[Math.floor(Math.random() * animations.length)];
    }

    function checkNewSMS() {
        $.post(smsAjax.ajax_url, { action: 'get_latest_sms' }, function(response) {
            console.log("AJAX response:", response);

            if (response.success) {
                let sms = response.data;

                if (lastId === null) {
                    lastId = sms.id;
                    console.log("Baseline SMS set:", lastId);
                    return;
                }

                if (sms.id !== lastId) {
                    console.log("New SMS detected!", sms);

                    const randomAnim = getRandomAnimation();

                    const newBoxHtml = `
                        <article class="sms_box animate__animated ${randomAnim} ${sms.color} p-3 text-white mb-4">
                            <header class="sms_badges mb-2">
                                <span class="sms_badge__location py-1 px-2">
                                    ${sms.location || 'empty'}
                                </span>
                            </header>

                            <p class="sms_text mb-2">${sms.text}</p>

                            <footer class="sms_meta">
                                <small class="sms_author">
                                    — <a
                                        class="user-link"
                                        data-user-id="${sms.author_id}"
                                        data-username="${sms.author_username}"
                                        href="${sms.profile_url}"
                                    >
                                        ${sms.author}
                                    </a>
                                    <time class="sms_date" datetime="${sms.datetime}">
                                        @${sms.date}
                                    </time>
                                </small>
                            </footer>
                        </article>
                        `;

                    if ($('.sms_box').length) {
                        $('.sms_box:first').before(newBoxHtml);
                    } else {
                        $('.sms_section').append(newBoxHtml);
                    }

                    lastId = sms.id;
                }
            }
        });
    }

    checkNewSMS();
    setInterval(checkNewSMS, 5000);
});