<div id="unifiedModalOverlay" class="d-none">
    <div id="unifiedModal" class="d-none">
    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia facilis fugiat sequi mollitia id velit esse! Facere, aliquid. Magni laborum a delectus modi aperiam quis vero iure! Accusamus qui nostrum quisquam! Soluta inventore qui accusantium eaque possimus voluptas, et dicta ea quia velit expedita. Minus veritatis inventore facere repudiandae excepturi?
    </div>
</div>

<style>
    #unifiedModalOverlay {
        margin: 0 auto;
        position: fixed;
        top: 50px;
        left: 0;
        /* background-color: rgba(0,0,0,0.5); */
        background-color: gray;
        height: 100%;
        width: 100%;
        display: flex;
        z-index: 50000;
        overflow: hidden;
        border-left: 2px red solid;
        border-right: 2px red solid;
    }
    #unifiedModal {
        position: absolute;
    }
</style>

<script>
    const modalOverlay = document.getElementById('unifiedModalOverlay');
    const unifiedModal = document.getElementById('unifiedModal');

    modalOverlay.addEventListener('click', function (e) {
    // Only close if click is on the overlay, not inside the modal content
    if (e.target !== modalOverlay) {
        modalOverlay.style.display = 'none';
    }
    });
</script>