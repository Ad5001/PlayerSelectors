<h1>
<img src="https://github.com/Ad5001/PlayerSelectors/raw/master/icon.png" width="36px" height="36px">&nbsp;&nbsp;PlayerSelectors - 
<a href="https://poggit.pmmp.io/p/PlayerSelectors"><img src="https://poggit.pmmp.io/shield.approved/PlayerSelectors"></a>
</h1>
Implementation of minecraft selectors into PocketMine!

<h1><img src="https://png.icons8.com/?id=365&size=1x">&nbsp;&nbsp;How to install?</h1>
1. Download the .phar file from the link given at the top of the repo
2. Put that phar into your "plugins" folder
3. Restart your server and Selectors should be reay to go!

<h1><img src="https://png.icons8.com/?id=3330&size=1x">&nbsp;&nbsp;Implemented features</h1>
PlayerSelectors implements all the default minecraft selectors:<br>   
- @a - All players<br>
- @p - Nearset player<br>
- @e - Entities<br>
- @r - Random player<br>  
- @s - Self/Sender<br>    
but also features a custom made one:<br>
Since PocketMine implements a multi world system, you can easily select players from your current world using the "@w" selector!
<br><br>
Minecraft also supports specific search parameters such as the distance from the sender, the count of matched player/entities, ...<br>
All the standard minecraft search params can be used with the syntax 

```
@<selector name>[key=param,other=value]
```

EXEPT the team & score params since the /scoreboard command isn't implemeted into pocketmine yet<br> Thought, one new params comes with this plugin: the "lvl" param which is for searching in a specific level.

Have fun!
