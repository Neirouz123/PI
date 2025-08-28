package entities;

public class Utilisateur {
    private int id;
    private String username;
    private int role;

    public Utilisateur(int id, String username, int role) {
        this.id = id;
        this.username = username;
        this.role = role;
    }

    public Utilisateur(int id, String username) {
        this.id = id;
        this.username = username;
    }

    public Utilisateur() {}

    public int getRole() {
        return role;
    }

    public int getId() {
        return id;
    }

    public String getUsername() {
        return username;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public void setRole(int role) {
        this.role = role;
    }
}