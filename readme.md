The Elevator (Or lift) kata is a superb kata to learn how to progressively create a fully working elevator system that can accept people on board and deliver them to each floor.

This kata should be executed in baby steps, this means, do not read the following steps before completing the previous ones. The objectives of baby steps is to allow you to think just enough and then work out the modifications in the next step to achieve the expected results.

You do not need to be backwards compliant in a baby steps kata, it would be too complex in many situations, the goal is to properly understand how to refactor your own code to achieve a result.

# Setup phase

> Note that this example is done using PHP so there are references to PHPUnit and Composer. Substitute does for your preferred language.

Ask a friend, a colleague or your team lead to guide you through this first step if you need help.

1. Create a new project
2. Setup project with docker `php:latest` or `php:8` 
   > I use PHPStorm, may not apply to your IDE!
3. Setup autoloader through `composer.json`
4. Require `phpunit/phpunit` and configure it

# Step 1

<details>
<summary>Expand this to view the conditions of success for this step.</summary>

In this step, we’ll create a simple elevator concept that can move to some hypothetical floor. This will be the base of our system.

**Create the Elevator concept**

- Elevators can be moved to a floor, you must pass them a target floor,
- Elevators that are already handling a request should not accept a new target until it arrives at its destination,
- Elevators must report current floor, state and direction,
- Elevator state can be “waiting” or “moving”,
- Current direction is the direction the elevator needed to move towards from current position, it should be expressed using “up”, “down” and “none”,
- Elevator must move when calling the act function and it must only move 1 floor at a time, this is important for future steps,
- Elevator must stop moving (Go back to waiting) when it reaches its floor, direction and target floor must also be reset to none.
</details>

# Step 2

<details>
<summary>Expand this to view the conditions of success for this step.</summary>

In this step, we’ll add the concept of floors where elevators can move to. Floors will also have buttons to call the elevator.

**Add a Floor concept to your project**

- Floors have a floor number that they expose
- Floors have between 0 and 2 buttons (none/up/down/both)
- If someone tries to press up and there is no such button, it should throw an exception
- If someone presses a button, the elevator should be called to this floor
- Floors need a reference to an elevator for now but this will be fixed in the next set.****
</details>

# Step 3

<details>
<summary>Expand this to view the conditions of success for this step.</summary>

In this step we introduce events which are important aspects when you want to create complex disconnected systems that interact together.

**New concepts**

- Event Pipeline,
- Tick Event,
- Door Event,
- Elevator Event,
- Floor Button Event,
- Speaker Event,
- Elevator Acts When The Clock Ticks Event Subscriber,
- Elevator Responds To Floor Button Event Subscriber,
- Speaker Beeps When Elevator Reaches A Floor Subscriber,
- Speaker Dings When Elevator Doors Start Opening Subscriber,
- Speaker.

**Event Pipeline**

- is a global singleton object that all objects have access to by getting its instance,
- to make it testable, you have to be able to set the static instance to null or a mock for example,
- event pipelines can receive requests to register subscribers for different events,
- when event pipeline receives a dispatch request, it should call the respond method from each subscriber.

**Elevators**

- must now offer a status that can be set to “moving”, “stopped”, “opening”, “open”, “closing”, “closed”,
- the direction must still stay the same if it hasn’t reached its target,
- must trigger Elevator Event every time the current flow changes with “floor-change” value,
- must trigger Door Event if the status changes to “opening”, “open”, “closing”, “closed”,
- must respond to tick events using a subscriber, the tick method still stays on the elevator but the shaft is not calling it anymore, the subscriber does,
- when responding to tick, it should evaluate if it reached it’s destination and change to stopped, then go through door events in order opening → open → closing → closed and then go back to moving if it hasn’t reached its destination.

**Floors**

- must now trigger a Floor button event when someone presses a button,
- The floor button event contains the direction requested and the floor number although direction is still useless,
- The method does not call the shaft’s call method anymore and the call method on shaft should not exist anymore,
- The elevator must register a subscriber for this event and decide if it wants to start moving towards that floor. That can only happen if it doesn’t already have a target. For now, elevators will not attempt to move to another destination once they are done.

**Speakers**

- A speaker is bound to an elevator,
- A speaker will be bound to different subscribers to
   - make a beep when the elevator moves to any new floor,
   - make a ding when the elevator doors start to open its doors,
- When a speaker makes a sound it triggers a Speaker Event with the proper “beep” or “ding” value.

> Important: These changes will break many floor tests because we are changing the way the elevator and the floor works together.
</details>

# Step 4

<details>
<summary>Expand this to view the conditions of success for this step.</summary>

At this point you have a working elevator that should be able to make some noise, move to different floors, open and close doors and move by itself. Now we’ll up the challenge a little more and ensure the elevator and floors all have a display that changes and we’ll start adding multiple elevators per floor.

**New concepts**

- Displays (2): An elevator display and a floor display
- Light indicators

**Elevators, speakers, displays, etc**

- Elevators must have an ID
- All events that pertain to a specific elevator must report that id
- All subscribers to elevator events must ensure they react on the proper elevator id
- Add tests where different elevators and devices exist and ensure the proper events are thrown

**Displays**

- Must either be:
   - bound to an elevator and display the elevator’s floor. Passengers must be able to refer to the display from the elevator, which means an accessor to it. The display must respond to events to update. The elevator cannot update the display directly.
   - bound to a floor and an elevator in which case it reports the elevator’s floor from the floor side. Passengers cannot refer to this display from the elevator but will refer to them from the floor provided they select which elevator’s display they want to see. Again, the display updates based on events, not on direct reference to the elevator

**Light indicators**

- Light indicators have an on or off state
- They are bound to a floor and elevator
- They turn on when the doors start to open
- They turn off when the doors start to close

**Floors**

- Must now sport a speaker and respond to elevator door opening events making a ring just like the speaker event for the elevator
- The speaker doesn’t need to be seen or be accessed
- You can now have multiple elevators on each floor, each elevator is to be accessed from it’s id or by enumerating the elevators
- Buttons don’t need to be pressed for each elevator, just one button calls an elevator
- Only one elevator must respond to each move request, it is up to you to decide how you want to do that but here are ideas:
   - Elevator controller which decides which elevator to respond and calls it, you issue a new event that the controller listens to
   - Floors manually elect one specific elevator (the closest for example) to respond to this request
</details>

# Optional challenges

<details>
<summary>Expand this to view the conditions of success for this step.</summary>

You can be proud of yourself to have completed this Kata, i hope you have learned a lot from this.

You now have a fully working elevator set, there are still things you can try go even deeper into the concept. Here are a few ideas for you:

- Make elevators stop at floors that request to go in the same direction as the elevator is going
- Add express elevators where some elevators will service only certain floors
- Add weights to passengers and simulate overweight elevators with buzzing sounds
- Add door/elevator jams

> Don’t hesitate to share other ideas with your team members.
</details>