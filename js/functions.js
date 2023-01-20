const password = 'ugOMs6M81'; // password 

async function getLog() {
    const amount = 30;
    let response = await fetch('api/index.php/git/log/?p=' + password + '&amount=' + amount);
    let data = await response.json();
    return data;
}

async function getCommits() {
    const amount = 30;
    let response = await fetch('api/index.php/git/commits/?p=' + password + '&amount=' + amount);
    let data = await response.json();
    return data;
}

async function revertCommit(commit) {
    if (!commit) return;
    let response = await fetch('api/index.php/git/revert/?p=' + password + '&commit=' + commit);
    let data = await response.json();
    return data;
}

async function pullChanges() {
    let response = await fetch('api/index.php/git/pull/?p=' + password + '&');
    let data = await response.json();
    return data;
}

async function addOrModifyVariable(variables) {
    if (!variables) return;
    const body = {
        variables: JSON.stringify(variables),
        password: password
    }
    let response = await fetch(
        'api/index.php/config/addOrModify',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: Object.keys(body).map(k => `${encodeURIComponent(k)}=${encodeURIComponent(body[k])}`).join('&')
        }
    );
    let data = await response.json();
    return data;
}

async function getVariables() {
    let response = await fetch('api/index.php/config/getVariables/?p=' + password);
    let data = await response.json();
    return data;
}

function loadCommitsList() {
    while (document.getElementById('lista').firstChild) {
        document.getElementById('lista').firstChild.remove()
    }
    getCommits().then((response) => {
        if (response.status == 200) {
            let first = true
            document.getElementById('repName').innerHTML = response.repo
            document.getElementById('repName').href = 'https://github.com/' + response.host + '/' + response
                .repo
            JSON.parse(response.commits).forEach(commit => {
                if (commit == '') return;
                let x = commit.split(' -');
                if (first) document.getElementById('commitAtual').innerHTML =
                    'Commit Atual: <small>' + x[0] + '</small>';
                document.getElementById('lista').insertAdjacentHTML("beforeend", '<p>[<span>' + x[
                    0] + '</span>]' + x[1] +
                    '</p>')
                first = false;
            });
        }
    })
    getVariables().then((response) => {
        document.getElementById('rep_host').value = response.variables.REP_HOST
        document.getElementById('rep_name').value = response.variables.REP_NAME
        document.getElementById('rep_branch').value = response.variables.REP_BRANCH
        document.getElementById('localfolder').value = response.variables.LOCALFOLDER

        document.getElementById('branchName').innerHTML = response.variables.REP_BRANCH || 'master/main'
    })
}

function loadLog() {
    while (document.getElementById('log').firstChild) {
        document.getElementById('log').firstChild.remove()
    }
    getLog().then((response) => {
        console.log(response)
        if (response.status == 200) {
            (response.log).forEach(log => {
                if (log == '') return;
                let m = log.message.replace('\n', '<br>');
                document.getElementById('log').insertAdjacentHTML("beforeend",
                    `<p><span>[${log.datetime}]</span> ${m}</p>`)
            });
        }
    })
}

function addClass(element, className) {
    element.className += ' ' + className;
}

function removeClass(element, className) {
    element.className = element.className.replace(
        new RegExp('( |^)' + className + '( |$)', 'g'), ' ').trim();
}

function openModal(title, message, buttons, configModal = false) {
    document.getElementById('titulo-modal').innerHTML = title;
    document.getElementById('modal-message').innerHTML = message;
    document.getElementById('cancelar').onclick = buttons[0]
    document.getElementById('confirmar').onclick = buttons[1]
    const configModals = document.getElementById('configInputs')
    
    if (!configModals.classList.contains('d-none')){
        addClass(configModals, 'd-none');
    }

    if (configModal){
        removeClass(document.getElementById('configInputs'), 'd-none');
    }
    
    addClass(document.getElementsByClassName('bg-load')[0], 'active');
    removeClass(document.getElementById('modal'), 'd-none')
}