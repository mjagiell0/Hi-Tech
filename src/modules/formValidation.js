function getElements(elementId) {
    const input = document.getElementById(elementId);
    const inputError = document.getElementById(elementId + "-error");
    return {input, inputError};
}

function clearElements(input, inputError) {
    input.classList.remove("error-input");
    inputError.textContent = "";
}

function showError(input, inputError, errorMessage) {
    input.classList.add("error-input");
    inputError.textContent = errorMessage;
}

export function validateInput(inputId, isValidate, errorMessage) {
    const {input, inputError} = getElements(inputId);
    clearElements(input, inputError);

    if (isValidate(input)) {
        return true;
    }

    showError(input, inputError, errorMessage);
    return false;
}

export function multiValidateInput(inputId, ...validators) {
    const {input, inputError} = getElements(inputId);
    clearElements(input, inputError);

    for (const { isValidate, errorMessage } of validators) {
        if (!isValidate(input)) {
            showError(input, inputError, errorMessage);
            return false;
        }
    }

    return true;
}