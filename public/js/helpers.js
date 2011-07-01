/**
 * Created by JetBrains PhpStorm.
 * User: Spav
 * Date: 04.04.11
 * Time: 23:24
 * To change this template use File | Settings | File Templates.
 */
function post_to_url(path, params, method) {
    method = method || "post"; // Set method to post by default, if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", params[key][0]);
        hiddenField.setAttribute("value", params[key][1]);

        form.appendChild(hiddenField);
    }

    document.body.appendChild(form);    // Not entirely sure if this is necessary
    form.submit();
}
