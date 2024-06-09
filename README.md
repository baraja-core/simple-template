<div align='center'>
  <picture>
    <source media='(prefers-color-scheme: dark)' srcset='https://cdn.brj.app/images/brj-logo/logo-regular.png'>
    <img src='https://cdn.brj.app/images/brj-logo/logo-dark.png' alt='BRJ logo'>
  </picture>
  <br>
  <a href="https://brj.app">BRJ organisation</a>
</div>
<hr>

Simple template
===============

Simple PHP templating system for user editable templates.

Idea
----

Most applications need to render templates that insert safely treated variables.

This library allows you to easily create templates that can be edited by the user. All templates are rendered in a secure manner that prevents security vulnerabilities from being created.

Each template is validated before rendering for the ability to automatically check the initialization of all variables and available data. You can also perform secure validation against test data before saving a new template.

Typical use: editable email templates or SMS messages.
