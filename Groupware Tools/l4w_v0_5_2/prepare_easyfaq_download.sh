# workaround für das Deployment, damit die
# folgenden Datein nicht automatisch geupdated werden.

rm -R custom
rm -R doc

rm -R modules/contacts
rm -R modules/docs
rm -R modules/notes
rm -R modules/stats
rm -R modules/tickets
rm -R modules/todos
rm -R modules/workflow

rm -R tests