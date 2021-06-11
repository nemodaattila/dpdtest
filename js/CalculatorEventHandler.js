/**
 * handles the html events
 */
class CalculatorEventHandler {
    /**
     * adds onclick event to the button (xmlhttprequest send)
     */
    init() {
        let button = document.getElementById("calcButton")
        button.addEventListener("click", () => {
            let coords = [
                [document.getElementById("LatitudeA").value, document.getElementById("LongitudeA").value],
                [document.getElementById("LatitudeB").value, document.getElementById("LongitudeB").value]
            ]
            let ac = new AjaxCaller();
            ac.setResponseFunction(this, "displayResult")
            ac.requestType = "POST"
            ac.targetUrl = "./calculator"
            ac.addCustomHeader('Content-Type', 'application/json')
            ac.postFields = coords;
            ac.send();
        })
    }

    /**
     * displays the result of the calculation
     * @param result data from http response
     */
    displayResult(result) {
        let es = document.getElementById('errorSpan')
        if (result['success'] === false)
        {
            es.hidden=false;
            es.innerHTML = result['errorMessage'];
            document.getElementById("pointCSpan").innerText = '';
            document.getElementById("pointDSpan").innerText = '';
            document.getElementById("perimeterSpan").innerText = '0';
            document.getElementById("areaSpan").innerText = '0';
            document.getElementById("priceSpan").innerText = '0';
        }
        else {
            es.hidden=true;
            document.getElementById("pointCSpan").innerText = result["cpoint"];
            document.getElementById("pointDSpan").innerText = result["dpoint"];
            document.getElementById("perimeterSpan").innerText = result["perimeter"];
            document.getElementById("areaSpan").innerText = result["area"];
            document.getElementById("priceSpan").innerText = result["price"];
        };
    }
}

new CalculatorEventHandler().init();
