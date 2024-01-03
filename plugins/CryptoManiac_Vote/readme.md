# Voting Plugin Documentation

## Table of Contents

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Usage](#usage)
5. [Admin Functionality](#admin-functionality)
6. [Code Structure](#code-structure)
7. [Troubleshooting](#troubleshooting)
8. [Changelog](#changelog)

## 1. Introduction <a name="introduction"></a>

The CryptoManiac Voting Plugin is an Object-Oriented Programming (OOP) WordPress plugin that enables website visitors to vote on articles. It implements a simple voting system with two actions - Yes and No, displaying results as an average percentage. The plugin uses Ajax requests to submit and process votes, ensuring a seamless and user-friendly experience.

## 2. Installation <a name="installation"></a>

Follow these steps to install the CryptoManiac Voting Plugin:

1. Download the plugin ZIP file.
2. Log in to your WordPress admin dashboard.
3. Navigate to "Plugins" > "Add New."
4. Click on the "Upload Plugin" button.
5. Choose the downloaded ZIP file and click "Install Now."
6. Activate the plugin.

## 3. Configuration <a name="configuration"></a>

The CryptoManiac Voting Plugin requires minimal configuration. After activation, the plugin will automatically display the voting feature at the end of each single post article.

## 4. Usage <a name="usage"></a>

### Voting Buttons

The plugin adds two voting buttons - Yes and No - at the end of each single post article. Visitors can click on these buttons to cast their votes.

### Results Display

After voting, visitors will see the average percentage of positive and negative votes. The voting buttons will remain inactive, displaying the visitor's vote even after a page refresh.

### Preventing Duplicate Votes

The plugin uses visitor fingerprinting (IP address) to prevent users from voting twice on the same article.

## 5. Admin Functionality <a name="admin-functionality"></a>

### Post Edit

When editing an article from the admin area, you can view the voting results in a meta widget. This meta widget, named "Voting Results," is available in the post edit screen's side panel.

## 6. Code Structure <a name="code-structure"></a>

The plugin follows an Object-Oriented Programming (OOP) structure for better organization and maintainability. Below are key components:

- **Main Plugin Class (`CryptoManiac_Vote.php`):** This class initializes the plugin, enqueues scripts, displays voting buttons, processes votes via Ajax, and handles admin functionality.

- **JavaScript (`js/CryptoManiac_Vote.js`):** This script handles Ajax requests for voting, updating UI elements dynamically.

- **Templates (`templates/voting-template.php`):** This file includes the HTML structure for displaying voting buttons, results, and handling user interactions.

## 7. Troubleshooting <a name="troubleshooting"></a>

If you encounter issues:

1. Ensure the plugin is activated.
2. Check for JavaScript errors in the browser console.
3. Verify that your theme supports the necessary hooks.

## 8. Changelog <a name="changelog"></a>

### Version 1.0

- Initial release of the Voting Plugin.

