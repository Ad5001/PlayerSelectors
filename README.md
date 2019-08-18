> The original developer is Ad5001, but because development is stopped, I develop it instead.

<h1>
<img src="https://github.com/fuyutsuki/PlayerSelectors/raw/master/assets/icon.png" width=72 height=72>&nbsp;&nbsp;PlayerSelectors
</h1>
Implementation of minecraft selectors into PocketMine!

## How to install?
1. Download the .phar file from the link given at the top of the repo
2. Put that phar into your "plugins" folder
3. Restart your server and Selectors should be reay to go!

## Implemented features
PlayerSelectors implements all the default minecraft selectors:
- @a - All players
- @p - Nearset player
- @e - Entities
- @r - Random player
- @s - Self/Sender  

### but also features a custom made one:  
Since PocketMine implements a multi-world-system, you can easily select players from your current world using the "@w" selector!  

Minecraft also supports specific search parameters such as the distance from the sender, the count of matched player/entities, ...<br>
All the standard minecraft search params can be used with the syntax 

```
@<selector name>[key=param,other=value]
```

EXEPT the team & score params since the /scoreboard command isn't implemeted into pocketmine yet<br> Thought, one new params comes with this plugin: the "lvl" param which is for searching in a specific level.

Have fun!
