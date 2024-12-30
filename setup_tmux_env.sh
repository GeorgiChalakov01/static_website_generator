cd ~/git/professional/ZaivodLogistics/

tmux new-session -d -s zed

tmux rename-window -t 0 'index'
tmux send-keys -t 'index' 'sh' C-m 'vim index.php' C-m

tmux new-window -t zed:1 -n 'components'
tmux send-keys -t 'components' 'sh' C-m 'vim component_framework.php' C-m

tmux new-window -t zed:2 -n 'db'
tmux send-keys -t 'db' 'sh' C-m 'vim refresh_db.php' C-m

tmux new-window -t zed:3 -n 'scripts'
tmux send-keys -t 'scripts' 'sh' C-m 'cd scripts' C-m

tmux new-window -t zed:4 -n 'styles'
tmux send-keys -t 'styles' 'sh' C-m 'cd styles' C-m

tmux new-window -t zed:5 -n 'git'

tmux new-window -t zed:6 -n 'log'
tmux send-keys -t 'log' 'sh' C-m 'php -S localhost:8080' C-m

tmux new-window -t zed:7 -n 'shell'
tmux send-keys -t 'shell' 'sh' C-m 'php refresh_db.php' C-m

tmux new-window -t zed:8 -n 'browser'
tmux send-keys -t 'browser' 'sh' C-m 'firefox --new-window localhost:8080' C-m

tmux attach-session -t zed:0
