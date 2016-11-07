# Setup Web App

### 1. Create an IAM role that has the PowerUserAccess policy set

### 2. Clone this repository: [ITMO-444-Repo](https://github.com/illinoistech-itm/tgidwani)

### 3. cd into this repository 
### 4. Run script: setup-web-app.sh
`./setup-web-app.sh "<key-name>" "<iam-profile-name>"`

- Replace **&lt;key-name&gt;** with the name of valid key to enable ssh logins into EC2 instances
- Replace **&lt;iam-profile-name&gt;** with the name of an valid IAM profile that has the PowerUserAccess policy set

****Note: Allows the web application to interact with AWS without having to hardcode credentials in the source code****

