/**
 * NotesLocator.java
 *
 * This file was auto-generated from WSDL
 * by the Apache Axis 1.2RC2 Nov 16, 2004 (12:19:44 EST) WSDL2Java emitter.
 */

package notes_pkg;

public class NotesLocator extends org.apache.axis.client.Service implements notes_pkg.Notes {

    public NotesLocator() {
    }


    public NotesLocator(org.apache.axis.EngineConfiguration config) {
        super(config);
    }

    // Use to get a proxy class for notesPort
    private java.lang.String notesPort_address = "http://localhost/leads4web4/webservices/notes.php";

    public java.lang.String getnotesPortAddress() {
        return notesPort_address;
    }

    // The WSDD service name defaults to the port name.
    private java.lang.String notesPortWSDDServiceName = "notesPort";

    public java.lang.String getnotesPortWSDDServiceName() {
        return notesPortWSDDServiceName;
    }

    public void setnotesPortWSDDServiceName(java.lang.String name) {
        notesPortWSDDServiceName = name;
    }

    public notes_pkg.NotesPortType getnotesPort() throws javax.xml.rpc.ServiceException {
       java.net.URL endpoint;
        try {
            endpoint = new java.net.URL(notesPort_address);
        }
        catch (java.net.MalformedURLException e) {
            throw new javax.xml.rpc.ServiceException(e);
        }
        return getnotesPort(endpoint);
    }

    public notes_pkg.NotesPortType getnotesPort(java.net.URL portAddress) throws javax.xml.rpc.ServiceException {
        try {
            notes_pkg.NotesBindingStub _stub = new notes_pkg.NotesBindingStub(portAddress, this);
            _stub.setPortName(getnotesPortWSDDServiceName());
            return _stub;
        }
        catch (org.apache.axis.AxisFault e) {
            return null;
        }
    }

    public void setnotesPortEndpointAddress(java.lang.String address) {
        notesPort_address = address;
    }

    /**
     * For the given interface, get the stub implementation.
     * If this service has no port for the given interface,
     * then ServiceException is thrown.
     */
    public java.rmi.Remote getPort(Class serviceEndpointInterface) throws javax.xml.rpc.ServiceException {
        try {
            if (notes_pkg.NotesPortType.class.isAssignableFrom(serviceEndpointInterface)) {
                notes_pkg.NotesBindingStub _stub = new notes_pkg.NotesBindingStub(new java.net.URL(notesPort_address), this);
                _stub.setPortName(getnotesPortWSDDServiceName());
                return _stub;
            }
        }
        catch (java.lang.Throwable t) {
            throw new javax.xml.rpc.ServiceException(t);
        }
        throw new javax.xml.rpc.ServiceException("There is no stub implementation for the interface:  " + (serviceEndpointInterface == null ? "null" : serviceEndpointInterface.getName()));
    }

    /**
     * For the given interface, get the stub implementation.
     * If this service has no port for the given interface,
     * then ServiceException is thrown.
     */
    public java.rmi.Remote getPort(javax.xml.namespace.QName portName, Class serviceEndpointInterface) throws javax.xml.rpc.ServiceException {
        if (portName == null) {
            return getPort(serviceEndpointInterface);
        }
        java.lang.String inputPortName = portName.getLocalPart();
        if ("notesPort".equals(inputPortName)) {
            return getnotesPort();
        }
        else  {
            java.rmi.Remote _stub = getPort(serviceEndpointInterface);
            ((org.apache.axis.client.Stub) _stub).setPortName(portName);
            return _stub;
        }
    }

    public javax.xml.namespace.QName getServiceName() {
        return new javax.xml.namespace.QName("urn:notes", "notes");
    }

    private java.util.HashSet ports = null;

    public java.util.Iterator getPorts() {
        if (ports == null) {
            ports = new java.util.HashSet();
            ports.add(new javax.xml.namespace.QName("urn:notes", "notesPort"));
        }
        return ports.iterator();
    }

    /**
    * Set the endpoint address for the specified port name.
    */
    public void setEndpointAddress(java.lang.String portName, java.lang.String address) throws javax.xml.rpc.ServiceException {
        if ("notesPort".equals(portName)) {
            setnotesPortEndpointAddress(address);
        }
        else { // Unknown Port Name
            throw new javax.xml.rpc.ServiceException(" Cannot set Endpoint Address for Unknown Port" + portName);
        }
    }

    /**
    * Set the endpoint address for the specified port name.
    */
    public void setEndpointAddress(javax.xml.namespace.QName portName, java.lang.String address) throws javax.xml.rpc.ServiceException {
        setEndpointAddress(portName.getLocalPart(), address);
    }

}
