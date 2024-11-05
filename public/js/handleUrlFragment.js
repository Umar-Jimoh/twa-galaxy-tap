const url = '/twa-galaxy-tap/src/controllers/HandleWebFragmentContr.php'

async function handleUrlFragment() {
    const urlFragment = window.location.hash.substring(1)
    const queryString = new URLSearchParams(urlFragment)
    const tgWebAppData = queryString.get('tgWebAppData')
    
    try {
            const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-type": "application/json; charset=UTF-8"
            },
            body: JSON.stringify({'webAppData': tgWebAppData}),
        })

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        if (data.username) {
            document.getElementById('username').innerText = data.username
        }

    } catch (e) {
        console.error('Error:', e)
        return null
        
    }
}

handleUrlFragment()