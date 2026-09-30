# BloomVocab 🌱

BloomVocab is a vocabulary-learning application built with **Laravel, Livewire, Flux, Tailwind CSS, and MySQL**.

The idea is simple:

> Every vocabulary word is a flower. 🌱
> Plant it, practice it, help it grow, and don't let it wilt.

Users add words they are learning, practice them through randomized tests, and track how well they remember each word over time.

Words that are regularly remembered become healthy and eventually **bloom**. Words that are ignored gradually **wilt** and eventually become **dead**, prompting the user to review them.

---

## Features

### Vocabulary management

Users can:

* Add vocabulary words
* Edit vocabulary
* Delete vocabulary
* View all vocabulary
* Search vocabulary
* Filter vocabulary by language
* Filter vocabulary by health
* View test frequency
* View correct/incorrect attempts
* View accuracy
* See when a word was last tested
* See when a word is due for review

### Multiple languages

Vocabulary is language-specific.

For example, a user can have:

```text
French
  bonjour → hello
  maison → house
  manger → to eat

Spanish
  hola → hello
  casa → house
  comer → to eat
```

The vocabulary list supports:

```text
All Languages
French
Spanish
...
```

Languages are discovered dynamically from the user's vocabulary rather than being permanently hard-coded.

The practice system can also be filtered by language.

---

# 🌸 Flower Learning System

Each vocabulary word has a health state.

```text
🌱 Seed
   ↓
🌿 Growing
   ↓
🌸 Blooming
```

If the word is ignored:

```text
🌸 Blooming
   ↓
🥀 Wilting
   ↓
💀 Dead
```

The word is **never automatically deleted** when it dies.

A dead word simply means that the user has neglected it and should practice it again.

Once the user starts remembering the word again, it can grow back.

---

## Word states

| State       | Meaning                                        |
| ----------- | ---------------------------------------------- |
| 🌱 Seed     | Newly added word                               |
| 🌿 Growing  | Word is being actively learned                 |
| 🌸 Blooming | Word is remembered consistently                |
| 🥀 Wilting  | Word has not been reviewed recently            |
| 💀 Dead     | Word has been neglected for an extended period |

The health state is calculated from the learning history rather than being permanently stored as a database value.

This means a word can automatically transition from:

```text
🌸 Blooming
    ↓
🥀 Wilting
    ↓
💀 Dead
```

as time passes.

---

# 🎯 Practice System

BloomVocab uses an adaptive practice system rather than selecting completely random words.

Words that need more attention receive a higher probability of being selected.

The selection considers factors such as:

* Time since the word was last tested
* Number of previous attempts
* Incorrect answers
* Correct answers
* Current review schedule
* Whether the word has never been tested

For example:

```text
Word A
Last tested: yesterday
Accuracy: 95%

Word B
Last tested: 10 days ago
Accuracy: 50%

Word C
Last tested: 30 days ago
Accuracy: 20%
```

Word C should receive considerably more practice attention than Word A.

The practice algorithm is deliberately implemented as a separate service so it can later be replaced with a more sophisticated spaced-repetition algorithm such as FSRS.

---

# 📊 Learning Statistics

Each vocabulary word tracks:

* Total number of tests
* Correct answers
* Incorrect answers
* Accuracy
* Last tested date
* Next review date
* Learning status

Example:

```text
French
────────────────────────────

bonjour

Translation:
hello

Tests:
17

Correct:
15

Incorrect:
2

Accuracy:
88%

Last tested:
Yesterday

Status:
🌸 Blooming
```

---

# 🥀 Neglected Words

BloomVocab actively identifies vocabulary that has been neglected.

The dashboard can display something like:

```text
🥀 Your garden needs attention

3 words are wilting.

You haven't practiced:

• pourtant
• cependant
• réussir
```

And:

```text
💀 5 words have died

These words haven't been reviewed recently.

Practice them to bring them back to life.
```

The purpose of the system is not to punish the user but to provide a visual reminder that vocabulary needs regular reinforcement.

---

# 🏗️ Technology Stack

BloomVocab uses:

* **PHP 8.3+**
* **Laravel 13**
* **Livewire 4**
* **Flux**
* **Tailwind CSS 4**
* **MySQL 8+**
* **Vite**
* **Blade**
* **Laravel Notifications**
* **PHPUnit / Laravel testing tools**

---

# 📁 Project Structure

The main application structure is:

```text
app/
├── Livewire/
│   ├── Dashboard.php
│   ├── Vocabulary/
│   │   ├── Index.php
│   │   ├── Create.php
│   │   └── Edit.php
│   └── Practice/
│       └── Session.php
│
├── Models/
│   ├── VocabularyWord.php
│   └── VocabularyAttempt.php
│
├── Notifications/
│   └── NeglectedVocabularyNotification.php
│
└── Services/
    ├── VocabularyHealthService.php
    └── PracticeSelectionService.php

database/
├── factories/
├── migrations/
└── seeders/

resources/
├── css/
│   └── app.css
│
└── views/
    ├── components/
    ├── layouts/
    └── livewire/
        ├── dashboard.blade.php
        ├── vocabulary/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   └── edit.blade.php
        └── practice/
            └── session.blade.php

routes/
└── web.php

tests/
├── Feature/
└── Unit/
```

---

# 🗄️ Database

BloomVocab primarily uses two vocabulary-related tables.

## vocabulary_words

Stores the current state of a vocabulary word.

Important fields include:

```text
id
user_id
language
word
translation
pronunciation
part_of_speech
example_sentence
notes
test_count
correct_count
incorrect_count
last_tested_at
next_review_at
created_at
updated_at
```

---

## vocabulary_attempts

Stores the complete practice history.

```text
id
user_id
vocabulary_word_id
correct
response_time_ms
created_at
updated_at
```

This separation is intentional.

`vocabulary_words` provides fast access to the current state while `vocabulary_attempts` provides the historical data needed for analytics and future spaced-repetition algorithms.

---

# 🔐 Authentication

BloomVocab is designed to use Laravel's authentication system.

Each vocabulary word belongs to a user:

```text
User
 │
 ├── Vocabulary Word
 ├── Vocabulary Word
 ├── Vocabulary Word
 │
 └── ...
```

Users therefore only see and practice their own vocabulary.

---

# 🌍 Language Filtering

The vocabulary list should default to:

```text
All Languages
```

A user can then select a specific language:

```text
All Languages
French
Spanish
German
Portuguese
Italian
...
```

The languages should be dynamically generated from the user's existing vocabulary.

For example, the application can query:

```php
$languages = VocabularyWord::query()
    ->where('user_id', auth()->id())
    ->distinct()
    ->orderBy('language')
    ->pluck('language');
```

This means adding a word in a new language automatically makes that language available in the UI.

---

# 🧪 Example Data

The application includes seed data for development.

Example vocabulary:

```text
French

bonjour       → hello
maison        → house
manger        → to eat
livre         → book
fromage       → cheese
```

Spanish:

```text
hola          → hello
casa          → house
comer         → to eat
libro         → book
queso         → cheese
```

The seeded data can be used to test:

* Language filtering
* Vocabulary listing
* Practice sessions
* Statistics
* Flower health
* Neglected-word detection

---

# ⚙️ Installation

## Requirements

Before installing BloomVocab, make sure you have:

* PHP 8.3 or newer
* Composer
* Node.js
* NPM
* MySQL 8 or newer
* Laravel installer (optional)

Check your versions:

```bash
php --version
composer --version
node --version
npm --version
mysql --version
```

---

# 🚀 Create the Laravel Application

If starting from scratch:

```bash
laravel new bloomvocab
```

Select the Livewire starter kit when prompted.

Then enter the project:

```bash
cd bloomvocab
```

Install PHP dependencies:

```bash
composer install
```

Install JavaScript dependencies:

```bash
npm install
```

---

# 🗄️ Configure MySQL

Create a database:

```sql
CREATE DATABASE bloomvocab;
```

Update `.env`:

```dotenv
APP_NAME=BloomVocab
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bloomvocab
DB_USERNAME=root
DB_PASSWORD=
```

Generate the application key:

```bash
php artisan key:generate
```

---

# 🏃 Run Migrations

Run:

```bash
php artisan migrate
```

For a completely fresh development database:

```bash
php artisan migrate:fresh
```

To migrate and seed demo data:

```bash
php artisan migrate:fresh --seed
```

---

# 🌱 Seed the Database

The seeders create development vocabulary.

Run:

```bash
php artisan db:seed
```

Or:

```bash
php artisan migrate:fresh --seed
```

---

# 🎨 Frontend

Run Vite:

```bash
npm run dev
```

In another terminal:

```bash
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

---

# ⚡ Production Build

Build frontend assets:

```bash
npm run build
```

Then configure your web server to point to Laravel's:

```text
public/
```

directory.

---

# 🔔 Neglected Vocabulary Notifications

BloomVocab includes support for detecting vocabulary that has not been practiced recently.

The application can periodically execute a command that identifies neglected words and sends notifications.

For example:

```bash
php artisan vocabulary:notify-neglected
```

For production, configure Laravel's scheduler.

A scheduled task can run once per day:

```php
Schedule::command('vocabulary:notify-neglected')
    ->daily();
```

The exact scheduling configuration should follow the Laravel version used by the deployment.

---

# 🧠 Practice Algorithm

The practice selection algorithm is implemented separately from the Livewire UI.

Conceptually, each word receives a priority score.

A simplified model is:

```text
priority =
    time_since_last_review
    + incorrect_answer_weight
    + overdue_weight
    - successful_recall_weight
```

This allows the system to prioritize vocabulary that needs attention.

### New words

Words that have never been tested receive an initial priority so that they enter the learning cycle.

### Correct answer

A correct answer increases the review interval.

### Incorrect answer

An incorrect answer increases the priority and causes the word to be reviewed sooner.

### Neglected word

The longer a word is ignored, the higher its priority becomes.

---

# 🔮 Future Spaced Repetition

The current algorithm is intentionally simple.

The architecture allows it to be replaced with a more advanced algorithm later.

Potential future implementations include:

* SM-2
* FSRS
* Leitner system
* Custom retention model
* Difficulty estimation
* Personalized review intervals

The Livewire practice interface should not need to change because the selection logic lives in:

```text
app/Services/PracticeSelectionService.php
```

---

# 🌸 Future Garden UI

The long-term vision is to make the dashboard feel like a real vocabulary garden.

For example:

```text
              🌸          🌱
        🌿          🌸
   🌱                         🥀

       🌸     🌿     🌸

              🌱

       Your Vocabulary Garden
```

Each flower represents a vocabulary word.

Possible interactions:

* Hover over a flower to see the word
* Click a flower to open its details
* Different growth stages based on learning health
* Wilting animation for neglected words
* Water/revive action
* Garden statistics
* Language-specific gardens
* Garden filtering

---

# 🧩 Flux

BloomVocab uses Flux for UI components.

Examples:

```blade
<flux:button>
    Add Word
</flux:button>
```

```blade
<flux:input
    wire:model="word"
    label="Word"
/>
```

```blade
<flux:select wire:model.live="language">
    <flux:select.option value="all">
        All Languages
    </flux:select.option>

    <flux:select.option value="French">
        French
    </flux:select.option>

    <flux:select.option value="Spanish">
        Spanish
    </flux:select.option>
</flux:select>
```

Use the Flux version installed by the project when adding additional components, since component APIs can differ between Flux releases.

Check the installed version with:

```bash
composer show livewire/flux
```

---

# ⚠️ Troubleshooting

## Vite cannot resolve `/bootstrap`

If you see:

```text
[plugin:vite:import-analysis]
Failed to resolve import "/bootstrap"
from "resources/js/app.js"
```

do not import Laravel's root `bootstrap/` directory from JavaScript.

Check:

```text
resources/js/app.js
```

and remove an incorrect import such as:

```js
import "/bootstrap";
```

The Laravel `bootstrap/` directory contains Laravel application bootstrap files and is not normally a frontend JavaScript module.

---

## Flux "Unhandled match case"

If you see:

```text
Unhandled match case of type string
```

inside Flux code around:

```php
match ($size) {
    'base' => ...
}
```

check the installed Flux version:

```bash
composer show livewire/flux
```

Then verify that the `size` value passed to the component is supported by that version.

For example:

```blade
<flux:button size="base">
```

rather than an unsupported value such as:

```blade
<flux:button size="md">
```

Avoid modifying files inside:

```text
vendor/livewire/flux/
```

The underlying problem should be fixed in the application's Blade components.

---

# 🧹 Clear Laravel Cache

When changing configuration, routes, views, or Livewire components, it can be useful to run:

```bash
php artisan optimize:clear
```

Then restart Vite:

```bash
npm run dev
```

---

# 🧪 Testing

Run the Laravel test suite:

```bash
php artisan test
```

Or:

```bash
vendor/bin/phpunit
```

Tests should cover at least:

* User authentication
* Creating vocabulary
* Editing vocabulary
* Deleting vocabulary
* Vocabulary ownership
* Language filtering
* Practice selection
* Correct answers
* Incorrect answers
* Test counters
* Review scheduling
* Flower health calculation
* Neglected vocabulary detection

---

# 🔒 Security Considerations

Vocabulary belongs to the authenticated user.

Queries should always scope vocabulary to:

```php
auth()->id()
```

Never trust a vocabulary ID supplied by the browser without checking ownership.

For example:

```php
VocabularyWord::query()
    ->where('user_id', auth()->id())
    ->findOrFail($id);
```

This prevents one user from accessing another user's vocabulary.

---

# 📈 Possible Future Features

The architecture is designed to support future improvements.

### Learning

* Spaced repetition
* Difficulty rating
* Multiple-choice questions
* Translation questions
* Reverse translation
* Listening exercises
* Pronunciation exercises
* Audio
* Example sentences
* AI-generated examples

### Languages

* Language-specific pronunciation
* CEFR levels
* Gender for nouns
* Verb conjugations
* Irregular verbs
* Grammar information
* Multiple translations

### Garden

* Full visual garden
* Flower animations
* Seasons
* Achievements
* Streaks
* Garden statistics
* Language-specific gardens

### Notifications

* Daily review reminders
* Wilting-word notifications
* Dead-word notifications
* Learning streak reminders
* Email notifications
* Browser notifications

### Analytics

* Daily learning statistics
* Weekly retention
* Accuracy over time
* Most difficult words
* Most neglected words
* Words learned
* Words revived
* Language comparison

---

# 🛠️ Development Workflow

Start Laravel:

```bash
php artisan serve
```

Start Vite:

```bash
npm run dev
```

Or use Laravel's combined development command if configured:

```bash
composer run dev
```

Run tests:

```bash
php artisan test
```

Clear caches:

```bash
php artisan optimize:clear
```

Create a migration:

```bash
php artisan make:migration create_example_table
```

Create a model:

```bash
php artisan make:model Example
```

Create a Livewire component:

```bash
php artisan make:livewire Example
```

---

# 📚 Architecture Principles

BloomVocab follows a few important architectural principles.

### Keep UI separate from learning logic

Livewire handles interaction and presentation.

Services handle learning logic.

```text
Livewire
   │
   ├── Vocabulary management
   │
   └── Practice session
          │
          ↓
PracticeSelectionService
          │
          ↓
Learning data
```

### Preserve learning history

Attempts should not be deleted simply because the current word state changes.

The history is valuable for:

* Analytics
* Review scheduling
* Retention calculations
* Future algorithms

### Don't delete neglected words

A "dead" flower represents forgotten vocabulary, not deleted vocabulary.

Users should always be able to revive it.

---

# 📄 License

This project can be licensed according to the requirements of the project owner.

---

# 🌱 Project Vision

BloomVocab is designed around a simple learning metaphor:

> **Vocabulary is a garden.**

Every new word is a seed.

Every successful review gives it water.

Repeated recall makes it grow.

Consistent practice makes it bloom.

Neglect makes it wilt.

Too much neglect makes it die.

But a dead flower can always be planted again.

The goal isn't simply to collect thousands of words.

The goal is to **remember them**.
