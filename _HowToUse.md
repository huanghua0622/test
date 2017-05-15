# How To Use

## 拉取远程仓库的更新

- 方法一：首先执行 `git fetch` 命令检查其他人的更新。如果有更新，则使用 `git merge --no-ff master` 命令合并更新到当前的 master 分支；
- 方法二：使用 `git pull` 直接拉取更改到当前分支。不推荐使用

## 推送更改到远程仓库

先使用 `git add .` 将所有文件添加到暂存区，再使用 `git commit -m "{msg}"` 命令提交版本仓库，{msg} 是提交信息，可以根据自己的喜好填写。最后使用 `git push` 命令推送到远程仓库。

## 如何获得共享文件夹里面的新仓库

复制地址栏的文件路径，比如 `V:\_GitRepo\words`。在你的电脑的某个文件夹，按住 `Shift + 右键`，打开命令提示符，键入 `git clone + 路径`，比如 `git clone V:\_GitRepo\words`，回车即可。

## 设置命令别名

如果觉得命令太长，可以自定命令别名。使用 `git config --global alias.[xx] xxxx`。此处表示用 xx 代替 xxxx。比如：`git config --global alias.[a] add`。当我回车这条命令后，以后就可以使用 `git a .` 代替 `git add .`

推荐配置：

- git config --global alias.[a] add
- git config --global alias.[cm] commit -m
- git config --global alias.[b] branch
...

**简便方法：**

直接进入用户目录 (C:\Users\{zy})，找到 .gitconfig 文件，用 sublime 等软件打开，按照 GITCONFIG.png 中的格式，在其中添入以下内容 (不带 ```)：

```
[alias]
# basic
  i = init
  a = add
  c = commit
  o = origin
  cl = clone
  st = status
  cm = commit -m
  sm = submodule

# push
  ps = push
  pso = push origin
  psc = push coding
  psom = push origin master
  psot = push origin --tags

# pull
  pl = pull
  plo = pull origin
  plom = pull origin master

# fetch
  fe = fetch
  feo = fetch origin

# branch
  b = branch
  bd = branch -d
  bD = branch -D

# tag
  t = tag
  td = tag -d

# merge
  mg = merge
  mgn = merge --no-ff

# checkout
  co = checkout
  cob = checkout -b

# reset
  rs = reset --hard
  rsl = reset --hard HEAD^

# remote
  rmt = remote

# log
  lg = log --color --graph --pretty=format:'%Cred%h%Creset -%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset' --abbrev-commit
  rlg = reflog
```

GITCONFIG.png:

![GITCONFIG](_GITCONFIG.png)

于是，一整套流程变成了：

1. git fe
2. git mgn origin/master
3. git a .
4. git cm ""
5. git ps
