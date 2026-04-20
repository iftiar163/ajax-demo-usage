console.log("File Loaded");

document.addEventListener('DOMContentLoaded', function(){
    const button = document.getElementById('ajax-demo-btn');
    if(button){
        button.addEventListener('click', async function(event){
            console.log("button Clicked")
            const formData = new FormData();
            formData.append('action', 'demo')
            const response = await fetch(ajdm.ajax_url, {
                method : "POST",
                body : formData
            })

            const jsonData = await response.json()
            console.log(jsonData)
        })
    }

    const currencyButton = document.getElementById('ajax-demo-currency-btn');
    if(currencyButton){
        currencyButton.addEventListener('click', async function(event){
            document.getElementById('ajax-demo-currency-result').innerHTML = "Fetching Current Rates.....";
            const formData = new FormData();
            formData.append('action', 'currency')
            formData.append('nonce',ajdm.currencyNonce)
            const response = await fetch(ajdm.ajax_url, {
                method : "POST",
                body : formData
            })

            const jsonData = await response.json()
            console.log(jsonData)

            if (jsonData.success) {
                let html = '<ul>';
                for (const currency in jsonData.data) {
                    html += `<li>BDT to ${currency} = ${jsonData.data[currency]}</li>`;
                }
                html += "</ul>";
                document.getElementById('ajax-demo-currency-result').innerHTML = html
            }
        })
    }

    const contactForm = document.getElementById('ajax-demo-contact-form')
    if (contactForm) {
        contactForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this)
            formData.append('action', 'contact')
            formData.append('nonce', ajdm.contactNonce)
            const response = await fetch(ajdm.ajax_url, {
                method: 'POST',
                body: formData
            });
            const resultDiv = document.getElementById('ajax-demo-contact-result');
            const data = await response.json();
            if (data.success) {
                resultDiv.innerHTML = '<p>' + data.data + '</p>';
                this.reset()
            } else {
                resultDiv.innerHTML = '<p>' + data.data + '</p>';
            }
        })
    }
})