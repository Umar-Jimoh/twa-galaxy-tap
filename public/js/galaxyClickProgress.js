const galaxy = document.getElementById("image")

galaxy.addEventListener('mousedown', (e) => galaxyClicked(e))
galaxy.addEventListener('mouseup', galaxyUnclicked)
galaxy.addEventListener('touchstart', galaxyClicked)
galaxy.addEventListener('touchend', galaxyUnclicked)


function galaxyClicked(event) {
    event.preventDefault()
    galaxy.classList.add('image-small')
}

function galaxyUnclicked() {
    galaxy.classList.remove('image-small')
}