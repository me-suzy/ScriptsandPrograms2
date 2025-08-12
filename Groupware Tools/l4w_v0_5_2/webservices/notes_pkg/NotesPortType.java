/**
 * NotesPortType.java
 *
 * This file was auto-generated from WSDL
 * by the Apache Axis 1.2RC2 Nov 16, 2004 (12:19:44 EST) WSDL2Java emitter.
 */

package notes_pkg;

public interface NotesPortType extends java.rmi.Remote {

    /**
     * Updates note in leads4web, creates if not existent
     */
    public java.lang.String updateNoteOnServer(java.lang.String login, java.lang.String md5Passwd, java.lang.String headline, java.lang.String content, java.lang.String sync_with, java.lang.String identifier, java.lang.String last_change, java.lang.String timeoffset) throws java.rmi.RemoteException;
}
