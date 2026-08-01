<?php
$content = file_get_contents('resources/views/admin/dashboard.blade.php');

// Replace data bindings
$dataStr = <<<'JS'
         x-data="{
             isAnalyzing: false,
             pagePath: '/',
             targetKeyword: '',
             recommendation: null,
             activeTasks: [],
             completedTasks: [],
             currentTaskType: null,
             showManualForm: false,
             async init() {
                 this.fetchHistory();
             },
             async fetchHistory() {
                 try {
                     let res = await fetch('/admin/seo/recommendations');
                     let raw = await res.json();
                     let active = [];
                     let completed = [];
                     raw.forEach(item => {
                         if (item.status === 'applied' && item.manual_status === 'selesai') {
                             completed.push(item);
                         } else {
                             if (item.status === 'pending') {
                                 active.push({...item, task_type: 'auto'});
                             }
                             if (item.manual_status !== 'selesai') {
                                 active.push({...item, task_type: 'programmer'});
                             }
                         }
                     });
                     this.activeTasks = active;
                     this.completedTasks = completed;
                 } catch(e) {}
             },
JS;

$content = preg_replace('/x-data="\{.*?\bthis\.fetchHistory\(\);\s*\},.*?\bcatch\(e\)\s*\{\}\s*\},/s', $dataStr . ',', $content);

file_put_contents('resources/views/admin/dashboard.blade.php', $content);
