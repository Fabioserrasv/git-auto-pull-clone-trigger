const commitRevertInput = document.getElementById('revertCommitId');
const rBtn = document.getElementById('revertBtn');
const pBtn = document.getElementById('pullBtn');
const cBtn = document.getElementById('changeVarBtn');
const logHtml = document.getElementById('log');

commitRevertInput.onchange = () => {
    let com = commitRevertInput.value ? commitRevertInput.value : '';
    document.getElementById('revertBtn').innerHTML = 'Revert to commit ' + com;
}

document.getElementsByClassName('bg-load')[0].onclick = () => {
    removeClass(document.getElementsByClassName('bg-load')[0], 'active');
    addClass(document.getElementById('modal'), 'd-none');
}

cBtn.onclick = () => {
    openModal(
        'Atualização do Ambiente',
        'Configure as variáveis de ambiente da aplicação.',
        [
            () => {
                getVariables().then((response) => {
                    document.getElementById('rep_host').value = response.variables.REP_HOST
                    document.getElementById('rep_name').value = response.variables.REP_NAME
                    document.getElementById('rep_branch').value = response.variables.REP_BRANCH
                    document.getElementById('localfolder').value = response.variables.LOCALFOLDER

                    document.getElementById('branchName').innerHTML = response.variables.REP_BRANCH || 'master/main'
                })
                removeClass(document.getElementsByClassName('bg-load')[0], 'active');
                addClass(document.getElementById('modal'), 'd-none')
            },
            () => {
                if (document.getElementById('rep_name').value) {
                    let variables = [
                        { name: 'rep_host', value: document.getElementById('rep_host').value },
                        { name: 'rep_name', value: document.getElementById('rep_name').value },
                        { name: 'rep_branch', value: document.getElementById('rep_branch').value },
                        { name: 'localfolder', value: document.getElementById('localfolder').value }
                    ];
                    addOrModifyVariable(variables).then((response) => {
                        if (response.status == 200) {
                            loadCommitsList();
                            loadLog();
                            removeClass(document.getElementsByClassName('bg-load')[0], 'active');
                            addClass(document.getElementById('modal'), 'd-none')
                        }
                    })
                }
            }
        ],
        true
    )
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
                    addClass(document.getElementById('loading-modal'), 'active');

                    revertCommit(document.getElementById('revertCommitId').value).then((response) => {
                        if (response.status == 200) {
                            loadCommitsList();
                            loadLog();
                            document.getElementById('revertCommitId').value = '';
                            document.getElementById('revertCommitId').dispatchEvent(new Event(
                                "change"));
                            removeClass(document.getElementById('loading-modal'), 'active');
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
                addClass(document.getElementById('loading-modal'), 'active');
                pullChanges().then((response) => {
                    if (response.status == 200) {
                        loadCommitsList();
                        loadLog();
                        removeClass(document.getElementsByClassName('bg-load')[0], 'active');
                        removeClass(document.getElementById('loading-modal'), 'active');
                        addClass(document.getElementById('modal'), 'd-none')
                    }
                })
            }
        ]
    );

}
var options = ''
const select = document.getElementById('templateSelect')
variablesTemplates.forEach((template, index) => {
    options += "<option value=\"" + index + "\">Repository: " + template.rep_name + " | Branch: " + (template.rep_branch || "master/main") + "</option>"
});
select.innerHTML = options

select.onchange = () => {
    document.getElementById("rep_host").value = variablesTemplates[select.value].rep_host
    document.getElementById("rep_name").value = variablesTemplates[select.value].rep_name
    document.getElementById("rep_branch").value = variablesTemplates[select.value].rep_branch
    document.getElementById("localfolder").value = variablesTemplates[select.value].localfolder
}

loadCommitsList();
loadLog();