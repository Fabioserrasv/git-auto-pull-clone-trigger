async function getLog() {
    const amount = 30;
    let response = await fetch('api/index.php/git/log/?p=ugOMs6M81&amount=' + amount);
    let data = await response.json();
    return data;
}

async function getCommits() {
    const amount = 30;
    let response = await fetch('api/index.php/git/commits/?p=ugOMs6M81&amount=' + amount);
    let data = await response.json();
    return data;
}

async function revertCommit(commit) {
    if (!commit) return;
    let response = await fetch('api/index.php/git/revert/?p=ugOMs6M81&commit=' + commit);
    let data = await response.json();
    return data;
}

async function pullChanges() {
    let response = await fetch('api/index.php/git/pull/?p=ugOMs6M81&');
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

function openModal(title, message, buttons) {
    document.getElementById('titulo-modal').innerHTML = title;
    document.getElementById('modal-message').innerHTML = message;
    document.getElementById('cancelar').onclick = buttons[0]
    document.getElementById('confirmar').onclick = buttons[1]

    addClass(document.getElementsByClassName('bg-load')[0], 'active');
    removeClass(document.getElementById('modal'), 'd-none')
}