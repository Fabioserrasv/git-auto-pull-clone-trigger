const commitRevertInput = document.getElementById('revertCommitId');
const rBtn = document.getElementById('revertBtn');
const pBtn = document.getElementById('pullBtn');
const logHtml = document.getElementById('log');

commitRevertInput.onchange = () => {
    let com = commitRevertInput.value ? commitRevertInput.value : '';
    document.getElementById('revertBtn').innerHTML = 'Revert to commit ' + com;
}

document.getElementsByClassName('bg-load')[0].onclick = () => {
    removeClass(document.getElementsByClassName('bg-load')[0], 'active');
    addClass(document.getElementById('modal'), 'd-none');
}

rBtn.onclick = () => {
    if (document.getElementById('revertCommitId').value == '') {
        document.getElementById('revertCommitId').focus();
        return;
    }
    openModal(
        'Atualização do Ambiente',
        'Você realmente deseja reverter seu ambiente para o commit: <span style="color:var(--color-scale-blue-3);">' + document.getElementById(
            'revertCommitId').value + '</span>?',
        [
            () => {
                removeClass(document.getElementsByClassName('bg-load')[0], 'active');
                addClass(document.getElementById('modal'), 'd-none')
            },
            () => {
                if (document.getElementById('revertCommitId').value) {
                    revertCommit(document.getElementById('revertCommitId').value).then((response) => {
                        if (response.status == 200) {
                            loadCommitsList();
                            loadLog();
                            document.getElementById('revertCommitId').value = '';
                            document.getElementById('revertCommitId').dispatchEvent(new Event(
                                "change"));
                            removeClass(document.getElementsByClassName('bg-load')[0], 'active');
                            addClass(document.getElementById('modal'), 'd-none')
                        }
                    })
                }
            }
        ]
    )
}

pBtn.onclick = () => {
    openModal(
        'Atualização do Ambiente',
        'Você realmente deseja atualizar seu ambiente para versão mais recente?',
        [
            () => {
                removeClass(document.getElementsByClassName('bg-load')[0], 'active');
                addClass(document.getElementById('modal'), 'd-none')
            },
            () => {
                pullChanges().then((response) => {
                    if (response.status == 200) {
                        loadCommitsList();
                        loadLog();
                        removeClass(document.getElementsByClassName('bg-load')[0], 'active');
                        addClass(document.getElementById('modal'), 'd-none')
                    }
                })
            }
        ]
    );

}


loadCommitsList();
loadLog();