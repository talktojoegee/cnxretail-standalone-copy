// Homepage hero carousel. Slide timing comes from the progress bar animation in cnx-home.css.
(function () {
    var root = document.querySelector('[data-showcase]');
    if (!root) {
        return;
    }

    var track = root.querySelector('[data-showcase-track]');
    var slides = Array.prototype.slice.call(root.querySelectorAll('[data-showcase-slide]'));
    var bars = Array.prototype.slice.call(root.querySelectorAll('[data-showcase-bar]'));
    var pauseButton = root.querySelector('[data-showcase-pause]');

    var current = 0;
    var userPaused = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var hovering = false;
    var keyboardFocused = false;

    function show(index) {
        var next = (index + slides.length) % slides.length;

        slides[current].classList.remove('is-active');
        bars[current].classList.remove('is-active');
        bars[current].removeAttribute('aria-current');

        slides[next].classList.add('is-active');
        bars[next].classList.add('is-active');
        bars[next].setAttribute('aria-current', 'true');

        current = next;
    }

    function updatePausedState() {
        root.classList.toggle('is-paused', userPaused || hovering || keyboardFocused);
        track.setAttribute('aria-live', userPaused ? 'polite' : 'off');
        pauseButton.setAttribute('aria-label', userPaused ? 'Play slideshow' : 'Pause slideshow');
        pauseButton.querySelector('i').className = userPaused ? 'ti-control-play' : 'ti-control-pause';
    }

    bars.forEach(function (bar, index) {
        bar.addEventListener('click', function () {
            if (index !== current) {
                show(index);
            }
        });
        bar.addEventListener('animationend', function () {
            show(current + 1);
        });
    });

    root.querySelector('[data-showcase-prev]').addEventListener('click', function () {
        show(current - 1);
    });
    root.querySelector('[data-showcase-next]').addEventListener('click', function () {
        show(current + 1);
    });
    pauseButton.addEventListener('click', function () {
        userPaused = !userPaused;
        updatePausedState();
    });

    // Hold the current slide while someone is reading it or moving through it by keyboard.
    root.addEventListener('pointerenter', function (event) {
        if (event.pointerType === 'mouse') {
            hovering = true;
            updatePausedState();
        }
    });
    root.addEventListener('pointerleave', function () {
        hovering = false;
        updatePausedState();
    });
    root.addEventListener('focusin', function (event) {
        keyboardFocused = event.target.matches(':focus-visible');
        updatePausedState();
    });
    root.addEventListener('focusout', function (event) {
        if (!root.contains(event.relatedTarget)) {
            keyboardFocused = false;
            updatePausedState();
        }
    });

    root.addEventListener('keydown', function (event) {
        if (event.key === 'ArrowLeft') {
            show(current - 1);
        } else if (event.key === 'ArrowRight') {
            show(current + 1);
        }
    });

    var touchStartX = 0;
    var touchStartY = 0;
    root.addEventListener('touchstart', function (event) {
        touchStartX = event.changedTouches[0].clientX;
        touchStartY = event.changedTouches[0].clientY;
    }, { passive: true });
    root.addEventListener('touchend', function (event) {
        var deltaX = event.changedTouches[0].clientX - touchStartX;
        var deltaY = event.changedTouches[0].clientY - touchStartY;
        if (Math.abs(deltaX) > 50 && Math.abs(deltaX) > Math.abs(deltaY)) {
            show(current + (deltaX < 0 ? 1 : -1));
        }
    }, { passive: true });

    updatePausedState();
})();
