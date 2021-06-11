/**
 * sends a http request and handler the response
 */
// https://gist.github.com/EtienneR/2f3ab345df502bd3d13e
class AjaxCaller {
    /**
     * the target url of the request
     * @private
     */
    _targetUrl;

    /**
     * the type of request (PUT, POST, ...)
     * @private
     */
    _requestType;

    /**
     * data sent with the address
     * @private
     */
    _postFields;

    /**
     * pointer to the caller object
     * @private
     */
    _callerObject;

    /**
     * caller object function to call in case of success (response code 200)
     * @private
     */
    _function;

    /**
     * custom header
     * @type {[]}
     * @private
     */
    _customHeader = [];

    /**
     * if false : in case of response error, there is no data passing to the caller object, error alert only
     * @type {boolean}
     * @private
     */
    _getResponseError = false;

    /**
     * if true: the response data is expected as JSON encoded, will be decoded
     * @type {boolean}
     * @private
     */
    _responseIsJSONEncoded = true;

    set getResponseError(value) {
        this._getResponseError = value;
    }

    addCustomHeader(name, value) {
        this._customHeader.push([name, value]);
    }

    set targetUrl(value) {
        this._targetUrl = value;
    }

    set requestType(value) {
        this._requestType = value.toUpperCase();
    }

    set postFields(value) {
        this._postFields = value;
    }

    /**
     * compiles the http request, sends it,
     * receives the response, converts it if necessary
     * and calls the set caller-object function
     */
    send() {
        let request = new XMLHttpRequest();
        request.open(this._requestType, this._targetUrl, true);
        if (this._customHeader !== []) {
            for (let [name, value] of this._customHeader) {
                request.setRequestHeader(name, value);
            }
        }
        request.onreadystatechange = () => {
            let resultData
            if (request.readyState !== 4)
                return;
            try {
                if (this._responseIsJSONEncoded === true) {
                    resultData = JSON.parse(request.responseText);
                } else
                    resultData = request.responseText;
            } catch (e) {
                resultData = request.responseText;
            }
            if (request.status !== 200 && request.status !== 304) {
                if (this._getResponseError === false) {
                    alert('HTTP error ' + request.status + ' ' + request.response);
                    return;
                }
            }
            this._callerObject[this._function](resultData);
        };
        if (request.readyState === 4)
            return;
        if (this._postFields === null) {
            request.send()
        } else
            request.send(JSON.stringify(this._postFields));
    }

    /**
     * sets the pointer of the caller class, and the function to call in case of success (response code 200)
     * @param object the class pointer
     * @param functionName the name of the function to call
     */
    setResponseFunction(object, functionName) {
        this._callerObject = object;
        this._function = functionName;
    }
}
