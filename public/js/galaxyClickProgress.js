const galaxy = document.getElementById("button")
const progress = document.getElementById('progress')

galaxy.addEventListener('mousedown', (e) => galaxyClicked(e))
galaxy.addEventListener('mouseup', galaxyUnclicked)
galaxy.addEventListener('touchstart', galaxyClicked)
galaxy.addEventListener('touchend', galaxyUnclicked)

let maxClicks = 10
let timeId = null
let galaxyClickable = true
const incrementalFactor = 3
let initialProgressWidth = progress.getBoundingClientRect().width
let adjustementValue = initialProgressWidth / maxClicks

async function galaxyClicked(event) {
    event.preventDefault()
    if (parseFloat(progress.style.width) <= adjustementValue) {
        return
    } 

    galaxy.classList.add('button-small')
    progress.style.width = `${progress.getBoundingClientRect().width - adjustementValue}px`
    let adjustedProgressWidth = parseFloat(progress.style.width)
    let newPoints = await updateUserPointInDatabase()
    points.innerText = newPoints

    if(timeId) {
        clearInterval(timeId)
    }
    updateProgressBar(adjustedProgressWidth)
}

function galaxyUnclicked() {
    galaxy.classList.remove('button-small')
}

function updateProgressBar(adjustedProgressWidth) {
    timeId = setInterval(() => {
        adjustedProgressWidth += adjustementValue * incrementalFactor; // Increment the width
        progress.style.width = `${adjustedProgressWidth}px`;

    if (adjustedProgressWidth >= initialProgressWidth) {
        clearInterval(timeId);
        progress.style.width = `${initialProgressWidth}px`;
        timeId = null; // Reset timeId
    }
}, 1000);
}

async function updateUserPointInDatabase() {
    const url = '/twa-galaxy-tap/src/controllers/HandleClickProgressContr.php'
    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-type": "application/json; charset=UTF-8"
                },
            body: JSON.stringify({'clicked': true}),
        })
    
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
    
        const data = await response.json();
        return data.points
        
        
    } catch (e) {
        console.error('Error:', e)       
    }
}